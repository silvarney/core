<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    properties: Array,
});

const selectedPropertyId = ref('');
const checkIn = ref('');
const checkOut = ref('');
const loading = ref(false);

const handleSearch = () => {
    if (!checkIn.value || !checkOut.value) return;
    
    router.get(route('public.search'), {
        check_in: checkIn.value,
        check_out: checkOut.value,
        property_id: selectedPropertyId.value
    }, {
        preserveScroll: true
    });
};
</script>

<template>
    <div class="max-w-4xl mx-auto bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-4 shadow-2xl relative">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <!-- Property Select -->
            <div class="flex flex-col items-start p-3 hover:bg-white/5 rounded-2xl transition-all cursor-pointer">
                <label class="text-[10px] uppercase font-bold text-indigo-400 mb-1">Localização</label>
                <select v-model="selectedPropertyId" class="bg-transparent border-none p-0 text-white font-medium focus:ring-0 w-full appearance-none">
                    <option value="" class="bg-slate-900">Todas as Propriedades</option>
                    <option v-for="p in properties" :key="p.id" :value="p.id" class="bg-slate-900">{{ p.name }}</option>
                </select>
            </div>

            <!-- Check-in -->
            <div class="flex flex-col items-start p-3 hover:bg-white/5 rounded-2xl transition-all cursor-pointer border-l border-white/5">
                <label class="text-[10px] uppercase font-bold text-indigo-400 mb-1">Check-in</label>
                <input type="date" v-model="checkIn" class="bg-transparent border-none p-0 text-white font-medium focus:ring-0 w-full" />
            </div>

            <!-- Check-out -->
            <div class="flex flex-col items-start p-3 hover:bg-white/5 rounded-2xl transition-all cursor-pointer border-l border-white/5">
                <label class="text-[10px] uppercase font-bold text-indigo-400 mb-1">Check-out</label>
                <input type="date" v-model="checkOut" class="bg-transparent border-none p-0 text-white font-medium focus:ring-0 w-full" />
            </div>

            <!-- Action -->
            <div class="flex items-center justify-end">
                <button 
                    @click="handleSearch"
                    :disabled="!checkIn || !checkOut"
                    class="w-full md:w-auto bg-white text-indigo-900 px-8 py-4 rounded-2xl font-black text-sm hover:scale-105 active:scale-95 transition-all shadow-xl shadow-white/10 disabled:opacity-50"
                >
                    PESQUISAR
                </button>
            </div>
        </div>
    </div>
</template>
