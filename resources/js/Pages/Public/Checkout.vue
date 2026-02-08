<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    type: Object,
    details: Object,
    auth: Object,
});

const form = useForm({
    accommodation_type_id: props.type.id,
    check_in: props.details.check_in,
    check_out: props.details.check_out,
    name: props.auth.user ? props.auth.user.name : '',
    email: props.auth.user ? props.auth.user.email : '',
    guests: 2,
});

const submit = () => {
    form.post(route('public.checkout.store'), {
        onSuccess: () => alert('Reserva confirmada!'),
    });
};
</script>

<template>
    <Head title="Finalizar Reserva" />

    <PublicLayout :auth="auth">
        <div class="max-w-5xl mx-auto px-6 py-20">
            <div class="flex flex-col lg:flex-row gap-16">
                <!-- Form -->
                <div class="flex-1">
                    <h1 class="text-4xl font-black font-outfit text-white mb-10">Seus Dados</h1>
                    
                    <form @submit.prevent="submit" class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-widest">Nome Completo</label>
                                <input v-model="form.name" type="text" class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all" required />
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-widest">E-mail</label>
                                <input v-model="form.email" type="email" class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all" required />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-widest">Número de Hóspedes</label>
                            <input v-model="form.guests" type="number" min="1" class="w-24 bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all" required />
                        </div>

                        <div class="pt-8">
                            <button 
                                type="submit"
                                :disabled="form.processing"
                                class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-black py-6 rounded-3xl text-lg shadow-2xl shadow-indigo-600/30 transition-all hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50"
                            >
                                {{ form.processing ? 'PROCESSANDO...' : 'CONFIRMAR RESERVA' }}
                            </button>
                            <p class="mt-4 text-center text-slate-500 text-xs">Ao confirmar, você concorda com nossas políticas de hospedagem.</p>
                        </div>
                    </form>
                </div>

                <!-- Summary Sidebar -->
                <div class="w-full lg:w-96">
                    <div class="bg-white/5 border border-white/10 rounded-[40px] p-10 space-y-8 sticky top-32">
                        <h2 class="text-2xl font-bold text-white uppercase tracking-tight">Resumo</h2>
                        
                        <div class="space-y-6">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="text-[10px] font-bold text-indigo-400 uppercase">{{ type.property.name }}</span>
                                    <h4 class="text-white font-bold uppercase">{{ type.name }}</h4>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 py-6 border-y border-white/5">
                                <div>
                                    <span class="text-[10px] font-bold text-slate-500 uppercase">Check-in</span>
                                    <p class="text-white font-medium">{{ details.check_in }}</p>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-slate-500 uppercase">Check-out</span>
                                    <p class="text-white font-medium">{{ details.check_out }}</p>
                                </div>
                            </div>

                            <div class="flex justify-between items-end">
                                <span class="text-slate-500 font-medium">Total</span>
                                <span class="text-4xl font-black text-white leading-none">R$ {{ details.total_price }}</span>
                            </div>
                        </div>

                        <div class="bg-indigo-500/10 border border-indigo-500/20 rounded-2xl p-4 flex gap-3">
                            <span class="text-xl">✨</span>
                            <p class="text-xs text-indigo-300 leading-relaxed">Você está economizando R$ 45,00 por reservar direto pelo portal.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
