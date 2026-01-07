// resources/js/plugins/primevue.js
import PrimeVue from "primevue/config";
import Aura from "@primevue/themes/aura"; // Tema padrão clean

// Opcional: Importar serviços globais como Toast ou Confirmation se precisar
import ToastService from "primevue/toastservice";
import ConfirmationService from "primevue/confirmationservice";

export default function (app) {
    app.use(PrimeVue, {
        theme: {
            preset: Aura,
            options: {
                prefix: "p",
                darkModeSelector: false,
                cssLayer: false,
            },
        },
    });

    app.use(ToastService);
    app.use(ConfirmationService);
}
