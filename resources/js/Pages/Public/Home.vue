<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import SearchForm from '@/Components/Public/SearchForm.vue';

defineProps({
    properties: Array,
    auth: Object,
});
</script>

<template>
    <Head title="Encontre sua Estadia Ideal" />

    <PublicLayout :auth="auth">
        <!-- Hero Section -->
        <section class="max-w-7xl mx-auto px-6 pt-20 pb-32 text-center">
            <h1 class="text-5xl md:text-7xl font-black font-outfit mb-6 bg-clip-text text-transparent bg-gradient-to-r from-white via-indigo-200 to-indigo-400 leading-tight">
                Encontre a sua <br/> próxima experiência.
            </h1>
            <p class="text-lg text-slate-400 max-w-2xl mx-auto mb-12">
                Estadias de luxo e pousadas exclusivas para momentos inesquecíveis. <br/>
                Reserve agora com as melhores tarifas garantidas.
            </p>

            <SearchForm :properties="properties" />
        </section>

        <!-- Properties Grid -->
        <section class="max-w-7xl mx-auto px-6 py-20">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="text-4xl font-black font-outfit text-white">Nossos Destinos</h2>
                    <p class="text-slate-500 mt-2 text-lg">Propriedades selecionadas para sua satisfação.</p>
                </div>
                <div class="hidden md:block h-px flex-1 mx-12 bg-white/5"></div>
                <a href="#" class="text-indigo-400 font-bold hover:underline tracking-tight">VER MAPA</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div v-for="property in properties" :key="property.id" class="group relative bg-slate-900/40 border border-white/5 rounded-[40px] overflow-hidden hover:border-indigo-500/50 transition-all duration-500 hover:-translate-y-2">
                    <div class="aspect-[4/5] bg-slate-800 relative overflow-hidden">
                        <div class="absolute inset-0 flex items-center justify-center text-slate-700 font-black text-6xl opacity-20">SG</div>
                        <!-- Gradient Overlay -->
                        <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-slate-950 to-transparent"></div>
                    </div>
                    
                    <div class="absolute bottom-0 inset-x-0 p-8">
                        <div class="flex justify-between items-end">
                            <div>
                                <h3 class="text-2xl font-bold text-white uppercase tracking-tighter">{{ property.name }}</h3>
                                <p class="text-slate-400 text-sm mt-1">{{ property.location }}</p>
                            </div>
                            <Link :href="route('public.property', property.id)" class="bg-indigo-600 p-4 rounded-2xl shadow-lg shadow-indigo-600/30 hover:bg-indigo-500 transition-colors">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="properties.length === 0" class="col-span-full py-32 text-center border-2 border-dashed border-white/5 rounded-[40px]">
                    <p class="text-slate-500 text-xl">Nenhuma propriedade disponível no momento.</p>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section class="border-y border-white/5 bg-slate-900/20 py-24">
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-16">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-white/5 rounded-3xl flex items-center justify-center mb-6 text-indigo-400 text-3xl">🛡️</div>
                    <h4 class="text-xl font-bold text-white mb-2">Reserva Segura</h4>
                    <p class="text-slate-500">Transações protegidas e confirmação imediata via e-mail.</p>
                </div>
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-white/5 rounded-3xl flex items-center justify-center mb-6 text-indigo-400 text-3xl">💎</div>
                    <h4 class="text-xl font-bold text-white mb-2">Exclusividade</h4>
                    <p class="text-slate-500">Acomodações de alto padrão com serviços personalizados.</p>
                </div>
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-white/5 rounded-3xl flex items-center justify-center mb-6 text-indigo-400 text-3xl">⚡</div>
                    <h4 class="text-xl font-bold text-white mb-2">Melhor Preço</h4>
                    <p class="text-slate-500">Garantimos a menor tarifa ao reservar direto pelo nosso portal.</p>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
