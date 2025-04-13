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
                weekNumbers: true,
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
                            tipoEvento: this.$wire.tipoEvento,
                            search: this.$wire.search
                        };
                    },
                    failure: function(error) {
                        console.error('Erro ao carregar eventos:', error);
                        alert('Erro ao carregar eventos! Verifique o console para mais detalhes.');
                    }
                },
                eventClick: (info) => {
                    const eventId = info.event.id;
                    this.$wire.verEvento(eventId);
                },
                select: (info) => {
                    const startDate = info.startStr.split('T')[0];
                    this.$wire.prepararNovoEvento(startDate);
                },
                eventContent: (arg) => {
                    const eventEl = document.createElement('div');
                    eventEl.classList.add('fc-event-content-custom');

                    // Adicionar ícone com base no tipo de evento
                    const iconEl = document.createElement('span');
                    iconEl.classList.add('event-icon', 'mr-1');

                    if (arg.event.extendedProps.tipoEvento === 'entrevista') {
                        iconEl.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>';
                    } else if (arg.event.extendedProps.tipoEvento === 'sessao') {
                        iconEl.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>';
                    } else {
                        iconEl.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>';
                    }

                    eventEl.appendChild(iconEl);

                    // Adicionar título
                    const titleEl = document.createElement('span');
                    titleEl.classList.add('event-title');
                    titleEl.innerText = arg.event.title;
                    eventEl.appendChild(titleEl);

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
                eventClassNames: (arg) => {
                    const classNames = [];

                    // Adicionar classe com base no status
                    if (arg.event.extendedProps.status === 'realizado') {
                        classNames.push('event-completed');
                    } else if (arg.event.extendedProps.status === 'cancelado') {
                        classNames.push('event-cancelled');
                    }

                    // Adicionar classe com base no tipo
                    if (arg.event.extendedProps.tipoEvento === 'entrevista') {
                        classNames.push('event-interview');
                    } else if (arg.event.extendedProps.tipoEvento === 'sessao') {
                        classNames.push('event-session');
                    }

                    return classNames;
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
