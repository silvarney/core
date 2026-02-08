<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    property: Object,
    auth: Object,
});
</script>

<template>
    <Head :title="property.name" />

    <PublicLayout :auth="auth">
        <!-- Hero Property -->
        <section class="relative h-[60vh] flex items-end">
            <div class="absolute inset-0 bg-slate-800">
                 <div class="absolute inset-0 flex items-center justify-center text-slate-700 font-black text-9xl opacity-10">SG</div>
                 <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
            </div>
            
            <div class="relative max-w-7xl mx-auto px-6 pb-12 w-full">
                <span class="text-indigo-400 font-bold uppercase tracking-widest text-sm">{{ property.location }}</span>
                <h1 class="text-6xl font-black font-outfit text-white mt-2 uppercase">{{ property.name }}</h1>
            </div>
        </section>

        <!-- Content -->
        <section class="max-w-7xl mx-auto px-6 py-20 grid grid-cols-1 lg:grid-cols-3 gap-16">
            <div class="lg:col-span-2">
                <h2 class="text-3xl font-black font-outfit text-white mb-8 border-b border-white/5 pb-6">Acomodações</h2>
                
                <div class="space-y-12">
                    <div v-for="type in property.accommodation_types" :key="type.id" class="group bg-white/5 rounded-[40px] p-8 border border-white/5 hover:border-indigo-500/30 transition-all">
                        <div class="flex flex-col md:flex-row gap-8">
                            <div class="w-full md:w-48 aspect-square bg-slate-800 rounded-3xl flex items-center justify-center text-slate-700 font-bold">IMAGE</div>
                            
                            <div class="flex-1">
                                <h3 class="text-2xl font-bold text-white uppercase">{{ type.name }}</h3>
                                <p class="text-slate-500 mt-2 leading-relaxed">Uma breve descrição sobre o conforto e a experiência de se hospedar nesta categoria.</p>
                                
                                <div class="flex flex-wrap gap-2 mt-6">
                                    <span v-for="amenity in type.amenities" :key="amenity.id" class="text-[10px] bg-indigo-500/10 text-indigo-300 px-3 py-1.5 rounded-full font-bold">
                                        {{ amenity.name }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Sidebar Sticky -->
            <div class="lg:col-span-1">
                <div class="bg-indigo-600 rounded-[40px] p-10 text-white sticky top-32 shadow-2xl shadow-indigo-600/20">
                    <h3 class="text-2xl font-black font-outfit mb-4">Reserve Agora</h3>
                    <p class="text-indigo-100 mb-8 opacity-80">Garantimos o melhor preço para reservas diretas.</p>
                    
                    <Link :href="route('home')" class="block text-center bg-white text-indigo-600 font-black py-5 rounded-2xl hover:scale-105 transition-transform active:scale-95 shadow-xl">
                        VER DISPONIBILIDADE
                    </Link>
                    
                    <div class="mt-8 pt-8 border-t border-white/20 space-y-4">
                        <div class="flex items-center gap-3 text-sm">
                            <span class="text-xl">✅</span>
                            <span>Confirmação Instantânea</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <span class="text-xl">💳</span>
                            <span>Pagamento Seguro</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
