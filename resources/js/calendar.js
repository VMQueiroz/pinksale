import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';
import ptBrLocale from '@fullcalendar/core/locales/pt-br';

document.addEventListener('alpine:init', () => {
    Alpine.data('calendarData', () => ({
        calendar: null,
        currentView: 'dayGridMonth',
        calendarInitialized: false,

        init() {
            // Inicializar o calendário após um pequeno atraso para garantir que o DOM esteja pronto
            setTimeout(() => {
                this.initCalendar();
            }, 100);

            // Escutar eventos do Livewire
            Livewire.on('refreshCalendar', () => {
                if (this.calendar) {
                    this.calendar.refetchEvents();
                } else if (!this.calendarInitialized) {
                    this.initCalendar();
                }
            });

            Livewire.on('eventAdded', () => {
                if (this.calendar) {
                    this.calendar.refetchEvents();
                }
            });

            Livewire.on('eventUpdated', () => {
                if (this.calendar) {
                    this.calendar.refetchEvents();
                }
            });

            // Adicionar evento para reinicializar o calendário quando o modo de visualização mudar para calendário
            Livewire.on('viewModeChanged', (mode) => {
                if (mode === 'calendar') {
                    setTimeout(() => {
                        if (!this.calendar) {
                            this.initCalendar();
                        } else {
                            this.calendar.render();
                        }
                    }, 100);
                }
            });
        },

        initCalendar() {
            const calendarEl = this.$refs.calendar;

            if (!calendarEl) return;

            // Marcar que estamos tentando inicializar o calendário
            this.calendarInitialized = true;

            this.calendar = new Calendar(calendarEl, {
                plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                initialView: this.currentView,
                locale: ptBrLocale,
                editable: true,
                selectable: true,
                selectMirror: true,
                dayMaxEvents: true,
                navLinks: true,
                businessHours: {
                    daysOfWeek: [1, 2, 3, 4, 5], // Segunda a sexta
                    startTime: '08:00',
                    endTime: '18:00',
                },
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: false
                },
                events: {
                    url: '/agenda/api/eventos',
                    method: 'GET',
                    extraParams: () => {
                        return {
                            tipoEvento: this.$wire.tipoEventoSelected,
                            search: this.$wire.search
                        };
                    },
                    success: function(events) {
                        console.log('Eventos carregados com sucesso:', events);
                        // Verificar as cores dos eventos
                        if (events.length > 0) {
                            console.log('Exemplo de evento:', events[0]);
                            console.log('Cor de fundo:', events[0].backgroundColor);
                            console.log('Cor da borda:', events[0].borderColor);
                            console.log('Tipo de evento:', events[0].extendedProps.tipoEvento);
                            console.log('Status:', events[0].extendedProps.status);
                        }
                    },
                    failure: function(error) {
                        console.error('Erro ao carregar eventos:', error);
                        alert('Erro ao carregar eventos! Verifique o console para mais detalhes.');
                    }
                },
                eventClick: (info) => {
                    const eventId = info.event.id;
                    this.$wire.verEvento(eventId);
                    // Dispatch o evento para abrir o modal
                    this.$dispatch('open-modal', 'ver-evento');
                },
                select: (info) => {
                    const startDate = info.startStr.split('T')[0];
                    this.$wire.prepararNovoEvento(startDate);
                    // Dispatch o evento para abrir o modal
                    this.$dispatch('open-modal', 'novo-evento');
                },
                eventContent: (arg) => {
                    const eventEl = document.createElement('div');
                    eventEl.classList.add('fc-event-content-custom');

                    // Criar um container para o ícone, título e o ícone de check (todos na mesma linha)
                    const titleContainer = document.createElement('div');
                    titleContainer.classList.add('event-title-container');
                    titleContainer.style.display = 'flex';
                    titleContainer.style.justifyContent = 'space-between';
                    titleContainer.style.alignItems = 'center';
                    titleContainer.style.width = '100%';

                    // Criar um container para o ícone e o título (lado esquerdo)
                    const leftContainer = document.createElement('div');
                    leftContainer.style.display = 'flex';
                    leftContainer.style.alignItems = 'center';
                    leftContainer.style.flexGrow = '1';
                    leftContainer.style.overflow = 'hidden';

                    // Adicionar ícone com base no tipo de evento
                    const iconEl = document.createElement('span');
                    iconEl.classList.add('event-icon', 'mr-1');
                    iconEl.style.flexShrink = '0';

                    if (arg.event.extendedProps.tipoEvento === 'entrevista') {
                        // Ícone de pessoa para entrevista
                        iconEl.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>';
                    } else if (arg.event.extendedProps.tipoEvento === 'sessao') {
                        // Ícone de grupo para sessão
                        iconEl.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>';
                    } else if (arg.event.extendedProps.tipoEvento === 'urna') {
                        // Ícone de caixa/urna
                        iconEl.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>';
                    } else {
                        // Ícone de calendário para outros tipos
                        iconEl.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>';
                    }

                    leftContainer.appendChild(iconEl);

                    // Adicionar título
                    const titleEl = document.createElement('span');
                    titleEl.classList.add('event-title');
                    titleEl.innerText = arg.event.title;
                    titleEl.style.overflow = 'hidden';
                    titleEl.style.textOverflow = 'ellipsis';
                    titleEl.style.whiteSpace = 'nowrap';
                    leftContainer.appendChild(titleEl);

                    // Adicionar o container esquerdo ao container principal
                    titleContainer.appendChild(leftContainer);

                    // Adicionar ícone de check para eventos realizados (lado direito)
                    if (arg.event.extendedProps.status === 'realizado') {
                        const checkIcon = document.createElement('span');
                        checkIcon.classList.add('event-check-icon');
                        checkIcon.style.flexShrink = '0';
                        checkIcon.style.marginLeft = '4px';
                        checkIcon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>';
                        titleContainer.appendChild(checkIcon);
                    }

                    eventEl.appendChild(titleContainer);

                    // Adicionar hora se for visualização de dia ou semana
                    if (this.calendar.view.type !== 'dayGridMonth') {
                        if (arg.event.extendedProps.local) {
                            const localEl = document.createElement('div');
                            localEl.classList.add('event-local', 'text-xs', 'mt-1');
                            localEl.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>' + arg.event.extendedProps.local;
                            eventEl.appendChild(localEl);
                        }

                        if (arg.event.extendedProps.contato) {
                            const contatoEl = document.createElement('div');
                            contatoEl.classList.add('event-contato', 'text-xs', 'mt-1');
                            contatoEl.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>' + arg.event.extendedProps.contato;
                            eventEl.appendChild(contatoEl);
                        }
                    }

                    return { domNodes: [eventEl] };
                },
                // Função removida pois não está sendo usada

                // Garantir que as cores definidas na API sejam usadas
                eventDidMount: function(info) {
                    // Logs de depuração removidos

                    // Verificar o status do evento
                    const status = info.event.extendedProps.status;
                    const isCancelled = status === 'cancelado';
                    const isCompleted = status === 'realizado';

                    // Adicionar atributo de data para facilitar a seleção via CSS
                    info.el.setAttribute('data-fc-event-status', status);

                    // Obter o tipo de evento para determinar a cor base
                    const tipoEvento = info.event.extendedProps.tipoEvento;
                    let corBase, corBorda;

                    // Definir cores base por tipo
                    switch(tipoEvento) {
                        case 'entrevista':
                            corBase = isCancelled || isCompleted ? 'rgba(59, 130, 246, 0.7)' : 'rgb(59, 130, 246)'; // Azul
                            corBorda = isCancelled || isCompleted ? 'rgba(37, 99, 235, 0.8)' : 'rgb(37, 99, 235)'; // Azul escuro
                            break;
                        case 'sessao':
                            corBase = isCancelled || isCompleted ? 'rgba(139, 92, 246, 0.7)' : 'rgb(139, 92, 246)'; // Roxo
                            corBorda = isCancelled || isCompleted ? 'rgba(124, 58, 237, 0.8)' : 'rgb(124, 58, 237)'; // Roxo escuro
                            break;
                        case 'urna':
                            corBase = isCancelled || isCompleted ? 'rgba(245, 158, 11, 0.7)' : 'rgb(245, 158, 11)'; // Laranja
                            corBorda = isCancelled || isCompleted ? 'rgba(217, 119, 6, 0.8)' : 'rgb(217, 119, 6)'; // Laranja escuro
                            break;
                        case 'outro':
                            corBase = isCancelled || isCompleted ? 'rgba(236, 72, 153, 0.7)' : 'rgb(236, 72, 153)'; // Rosa
                            corBorda = isCancelled || isCompleted ? 'rgba(219, 39, 119, 0.8)' : 'rgb(219, 39, 119)'; // Rosa escuro
                            break;
                        default:
                            corBase = isCancelled || isCompleted ? 'rgba(107, 114, 128, 0.7)' : 'rgb(107, 114, 128)'; // Cinza
                            corBorda = isCancelled || isCompleted ? 'rgba(75, 85, 99, 0.8)' : 'rgb(75, 85, 99)'; // Cinza escuro
                    }

                    if (isCancelled) {

                        // Adicionar a classe para texto tachado
                        info.el.classList.add('event-cancelled');

                        // Forçar o texto tachado em todos os elementos filhos
                        const titleElements = info.el.querySelectorAll('.fc-event-title, .fc-sticky, .fc-event-time');
                        titleElements.forEach(el => {
                            el.style.setProperty('text-decoration', 'line-through', 'important');
                            el.style.setProperty('text-decoration-thickness', '2px', 'important');
                        });

                        // Se não encontrou elementos, aplicar ao próprio elemento do evento
                        if (titleElements.length === 0) {
                            info.el.style.setProperty('text-decoration', 'line-through', 'important');
                            info.el.style.setProperty('text-decoration-thickness', '2px', 'important');
                        }

                        // Aplicar a cor base com opacidade diretamente no elemento
                        info.el.style.setProperty('background-color', corBase, 'important');
                        info.el.style.setProperty('border-color', corBorda, 'important');

                        // Forçar a remoção de qualquer cor vermelha
                        setTimeout(() => {
                            info.el.style.setProperty('background-color', corBase, 'important');
                            info.el.style.setProperty('border-color', corBorda, 'important');
                        }, 10);
                    } else if (isCompleted) {

                        // Adicionar a classe para eventos realizados (apenas mais claros, sem tachado)
                        info.el.classList.add('event-completed');

                        // Aplicar a cor base com opacidade diretamente no elemento
                        info.el.style.setProperty('background-color', corBase, 'important');
                        info.el.style.setProperty('border-color', corBorda, 'important');
                    }

                    // Para eventos pendentes, aplicar as cores normais
                    if (!isCancelled && !isCompleted) {
                        // Aplicar a cor base diretamente no elemento
                        info.el.style.setProperty('background-color', corBase, 'important');
                        info.el.style.setProperty('border-color', corBorda, 'important');
                    }

                    // Logs de depuração removidos
                },

                // Manipulador para quando um evento é arrastado e solto
                eventDrop: function(info) {
                    const evento = info.event;
                    const id = evento.id;
                    const start = evento.start;
                    const end = evento.end;

                    // Confirmar a ação com o usuário
                    if (!confirm('Tem certeza que deseja mover este evento?')) {
                        info.revert(); // Reverter a ação se o usuário cancelar
                        return;
                    }

                    // Enviar a atualização para o servidor
                    fetch('/agenda/api/eventos/atualizar', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            id: id,
                            start: start ? start.toISOString() : null,
                            end: end ? end.toISOString() : null
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Notificar o usuário do sucesso
                            Livewire.dispatch('notify', {
                                type: 'success',
                                message: 'Evento atualizado com sucesso!'
                            });
                        } else {
                            // Reverter a ação e mostrar erro
                            info.revert();
                            Livewire.dispatch('notify', {
                                type: 'error',
                                message: data.message || 'Erro ao atualizar evento.'
                            });
                        }
                    })
                    .catch(error => {
                        // Reverter a ação e mostrar erro
                        info.revert();
                        console.error('Erro ao atualizar evento:', error);
                        Livewire.dispatch('notify', {
                            type: 'error',
                            message: 'Erro ao atualizar evento. Tente novamente.'
                        });
                    });
                },

                // Manipulador para quando um evento é redimensionado
                eventResize: function(info) {
                    const evento = info.event;
                    const id = evento.id;
                    const start = evento.start;
                    const end = evento.end;

                    // Confirmar a ação com o usuário
                    if (!confirm('Tem certeza que deseja redimensionar este evento?')) {
                        info.revert(); // Reverter a ação se o usuário cancelar
                        return;
                    }

                    // Enviar a atualização para o servidor
                    fetch('/agenda/api/eventos/atualizar', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            id: id,
                            start: start ? start.toISOString() : null,
                            end: end ? end.toISOString() : null
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Notificar o usuário do sucesso
                            Livewire.dispatch('notify', {
                                type: 'success',
                                message: 'Evento atualizado com sucesso!'
                            });
                        } else {
                            // Reverter a ação e mostrar erro
                            info.revert();
                            Livewire.dispatch('notify', {
                                type: 'error',
                                message: data.message || 'Erro ao atualizar evento.'
                            });
                        }
                    })
                    .catch(error => {
                        // Reverter a ação e mostrar erro
                        info.revert();
                        console.error('Erro ao atualizar evento:', error);
                        Livewire.dispatch('notify', {
                            type: 'error',
                            message: 'Erro ao atualizar evento. Tente novamente.'
                        });
                    });
                }
            });

            try {
                console.log('Renderizando calendário...');
                this.calendar.render();
                console.log('Calendário renderizado com sucesso!');
            } catch (error) {
                console.error('Erro ao renderizar o calendário:', error);
                // Tentar reinicializar o calendário em caso de erro
                setTimeout(() => {
                    this.calendar = null;
                    this.initCalendar();
                }, 200);
            }
        },

        changeView(viewName) {
            if (this.calendar) {
                this.calendar.changeView(viewName);
                this.currentView = viewName;
            }
        }
    }));
});
