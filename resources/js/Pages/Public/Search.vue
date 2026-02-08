<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import SearchForm from '@/Components/Public/SearchForm.vue';

const props = defineProps({
    results: Array,
    filters: Object,
    auth: Object,
});

const form = useForm({
    accommodation_id: '',
    check_in: props.filters.check_in,
    check_out: props.filters.check_out,
});

const bookNow = (result) => {
    router.get(route('public.checkout'), {
        accommodation_type_id: result.type.id,
        check_in: result.check_in,
        check_out: result.check_out
    });
};
</script>

<template>
    <Head title="Disponibilidade" />

    <PublicLayout :auth="auth">
        <div class="max-w-7xl mx-auto px-6 py-12">
            <!-- Header Result -->
            <div class="mb-12 border-b border-white/5 pb-12">
                <h1 class="text-4xl font-black font-outfit text-white mb-4">Unidades Disponíveis</h1>
                <p class="text-slate-500">
                    Exibindo resultados para <span class="text-indigo-400 font-bold">{{ filters.check_in }}</span> até <span class="text-indigo-400 font-bold">{{ filters.check_out }}</span>.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
                <!-- Sidebar Filters (Summary) -->
                <div class="lg:col-span-1">
                    <div class="bg-white/5 border border-white/10 rounded-3xl p-8 sticky top-32">
                        <h3 class="text-lg font-bold text-white mb-6">Sua Busca</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="text-[10px] uppercase font-bold text-slate-500">Check-in</label>
                                <p class="text-white">{{ filters.check_in }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] uppercase font-bold text-slate-500">Check-out</label>
                                <p class="text-white">{{ filters.check_out }}</p>
                            </div>
                        </div>
                        <Link :href="route('home')" class="mt-8 block text-center bg-white/5 hover:bg-white/10 text-white text-xs font-bold py-3 rounded-xl transition-colors">
                            ALTERAR BUSCA
                        </Link>
                    </div>
                </div>

                <!-- Results List -->
                <div class="lg:col-span-3 space-y-8">
                    <div v-for="result in results" :key="result.type.id" class="group bg-slate-900/40 border border-white/5 rounded-[40px] overflow-hidden grid grid-cols-1 md:grid-cols-3 hover:border-indigo-500/50 transition-all duration-300">
                        <div class="aspect-[4/3] md:aspect-auto bg-slate-800 relative">
                             <div class="absolute inset-0 flex items-center justify-center text-slate-700 font-black text-6xl opacity-20">SG</div>
                        </div>
                        
                        <div class="col-span-2 p-8 flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start">
                                    <div>
                                        <span class="text-indigo-400 text-[10px] font-bold uppercase tracking-widest">{{ result.type.property.name }}</span>
                                        <h3 class="text-2xl font-bold text-white mt-1 uppercase">{{ result.type.name }}</h3>
                                    </div>
                                    <span class="bg-green-500/20 text-green-400 text-[10px] px-3 py-1 rounded-full font-bold">DISPONÍVEL</span>
                                </div>

                                <div class="flex flex-wrap gap-3 mt-6">
                                    <span v-for="amenity in result.type.amenities" :key="amenity.id" class="text-[10px] bg-white/5 text-slate-400 px-3 py-1 rounded-lg">
                                        {{ amenity.name }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-8 pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-end md:items-center gap-6">
                                <div>
                                    <span class="text-xs text-slate-500">Preço Total (Estadia)</span>
                                    <p class="text-4xl font-black text-white">R$ {{ result.total_price }}</p>
                                </div>
                                <button 
                                    @click="bookNow(result)"
                                    class="w-full md:w-auto bg-white text-indigo-900 px-10 py-4 rounded-2xl font-black text-sm hover:scale-105 active:scale-95 transition-all shadow-xl shadow-white/10"
                                >
                                    RESERVAR AGORA
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- No Results -->
                    <div v-if="results.length === 0" class="py-32 text-center bg-white/5 border border-dashed border-white/10 rounded-[40px]">
                        <p class="text-slate-500 text-xl font-medium tracking-tight">Poxa! Não encontramos unidades disponíveis para essas datas.</p>
                        <p class="text-slate-600 mt-2">Tente alterar o período de sua estadia.</p>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
