
import './bootstrap';
import './calendar';


// Importar os plugins do Alpine
// import Alpine from 'alpinejs'; // REMOVIDO - Livewire deve fornecer
// import Focus from '@alpinejs/focus';      // REMOVIDO TEMPORARIAMENTE
// import Intersect from '@alpinejs/intersect'; // REMOVIDO TEMPORARIAMENTE

// Registrar os plugins usando window.Alpine (que Livewire deve definir)
// É mais seguro fazer isso dentro do listener livewire:initialized
// window.Alpine.plugin(Focus); 
// window.Alpine.plugin(Intersect);

// Listener para o evento 'notify' (REMOVIDO - Implementar sistema de toast real aqui)
// window.addEventListener('notify', event => {
//     console.log('Notification event received:', event.detail);
//     if (event.detail && event.detail[0] && event.detail[0].message) {
//         alert(`Notificação Recebida:\nTipo: ${event.detail[0].type}\nMensagem: ${event.detail[0].message}`);
//     } else {
//         alert('Notificação recebida, mas formato inesperado.');
//         console.log('Detalhes do evento notify inesperado:', event.detail);
//     }
// });

// Iniciar o Alpine (Livewire geralmente faz isso)
// Acessando via window.Alpine
document.addEventListener('alpine:init', () => {
    // console.log('Alpine initializing...'); // Log removido
    // Registrar plugins aqui é mais seguro se Alpine for carregado de forma assíncrona
    // window.Alpine.plugin(Focus);      // REMOVIDO TEMPORARIAMENTE
    // window.Alpine.plugin(Intersect); // REMOVIDO TEMPORARIAMENTE
});

// Se o Alpine.start() for necessário explicitamente (geralmente não com Livewire v3+)
// document.addEventListener('DOMContentLoaded', () => {
//    if (window.Alpine) {
//        window.Alpine.start();
//        console.log('Alpine started explicitly.');
//    } else {
//        console.error('Alpine not found on window object.');
//    }
// });


document.addEventListener('livewire:initialized', (event) => {
    // console.log('Livewire initialized.'); // Log removido
    // O Alpine já deve estar inicializado pelo Livewire neste ponto.
    // Podemos tentar registrar os plugins aqui também como fallback.
    if (window.Alpine) {
         try {
             // window.Alpine.plugin(Focus);      // REMOVIDO TEMPORARIAMENTE
             // window.Alpine.plugin(Intersect); // REMOVIDO TEMPORARIAMENTE
             // console.log('Alpine plugins (commented out) registered via livewire:initialized.'); // Log removido
         } catch (e) {
             // console.warn('Could not register Alpine plugins (commented out) via livewire:initialized:', e); // Log removido
         }
    } else {
        console.error('Alpine not found when Livewire initialized.');
    }
});
