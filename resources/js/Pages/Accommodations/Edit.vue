<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import ToggleSwitchSimple from '@/Components/ToggleSwitchSimple.vue';

const props = defineProps({
    accommodation: Object,
    accommodationTypes: Array,
});

const form = useForm({
    accommodation_type_id: props.accommodation.accommodation_type_id,
    name: props.accommodation.name,
    status: props.accommodation.status,
    // Descrição e Comodidades (em ordem alfabética)
    air_conditioning: props.accommodation.air_conditioning || 0,
    bathroom: props.accommodation.bathroom || 0,
    bed_types: props.accommodation.bed_types || [],
    breakfast_included: props.accommodation.breakfast_included || 0,
    coffee_maker: props.accommodation.coffee_maker || 0,
    cooktop: props.accommodation.cooktop || 0,
    closet: props.accommodation.closet || 0,
    double_bed: props.accommodation.double_bed || 0,
    fireplace: props.accommodation.fireplace || 0,
    grill: props.accommodation.grill || 0,
    hydromassage: props.accommodation.hydromassage || 0,
    microwave: props.accommodation.microwave || 0,
    mini_pool: props.accommodation.mini_pool || 0,
    pool: props.accommodation.pool || 0,
    refrigerator: props.accommodation.refrigerator || 0,
    single_bed: props.accommodation.single_bed || 0,
    tv: props.accommodation.tv || 0,
    wifi: props.accommodation.wifi || 0,
    wine_cellar: props.accommodation.wine_cellar || 0,
    mezzanine: props.accommodation.mezzanine || 0,
});

const submit = () => {
    form.put(route('accommodations.update', props.accommodation.id), {
        onFinish: () => {},
    });
};
</script>

<template>
    <Head title="Editar Unidade" />

    <AppLayout title="Editar Unidade (Acomodação)">

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <!-- Accommodation Type -->
                            <div>
                                <InputLabel for="accommodation_type_id" value="Tipo de Acomodação (e Propriedade)" />
                                <select
                                    id="accommodation_type_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    v-model="form.accommodation_type_id"
                                    required
                                >
                                    <option v-for="type in accommodationTypes" :key="type.id" :value="type.id">
                                        {{ type.name }} ({{ type.property?.name }})
                                    </option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.accommodation_type_id" />
                            </div>

                            <!-- Name -->
                            <div class="mt-4">
                                <InputLabel for="name" value="Nome/Número (ex: Quarto 101, Chalé 05)" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <!-- Status -->
                            <div class="mt-4">
                                <InputLabel for="status" value="Status" />
                                <select
                                    id="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    v-model="form.status"
                                    required
                                >
                                    <option value="available">Disponível</option>
                                    <option value="occupied">Ocupado</option>
                                    <option value="maintenance">Manutenção</option>
                                    <option value="cleaning">Limpeza</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.status" />
                            </div>

                            <!-- Descrição e Comodidades -->
                            <div class="mt-6 border-t pt-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-6">Descrição e Comodidades</h3>
                                
                                <div class="space-y-8">
                                    <!-- Camas e Dormitório -->
                                    <div class="bg-gray-50 rounded-lg p-6">
                                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                            </svg>
                                            Camas e Dormitório
                                        </h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                            <!-- Cama de Casal -->
                                            <div>
                                                <InputLabel for="double_bed" value="Cama de Casal" />
                                                <select
                                                    id="double_bed"
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    v-model="form.double_bed"
                                                >
                                                    <option v-for="n in 11" :key="n-1" :value="n-1">{{ n-1 }}</option>
                                                </select>
                                                <InputError class="mt-2" :message="form.errors.double_bed" />
                                            </div>

                                            <!-- Cama de Solteiro -->
                                            <div>
                                                <InputLabel for="single_bed" value="Cama de Solteiro" />
                                                <select
                                                    id="single_bed"
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    v-model="form.single_bed"
                                                >
                                                    <option v-for="n in 11" :key="n-1" :value="n-1">{{ n-1 }}</option>
                                                </select>
                                                <InputError class="mt-2" :message="form.errors.single_bed" />
                                            </div>

                                            <!-- Closet -->
                                            <div class="flex items-center">
                                                <ToggleSwitchSimple 
                                                    id="closet" 
                                                    label="Closet" 
                                                    v-model="form.closet" 
                                                />
                                                <InputError class="mt-2" :message="form.errors.closet" />
                                            </div>

                                            <!-- Tipos de Cama -->
                                            <div class="md:col-span-2 lg:col-span-3">
                                                <InputLabel for="bed_types" value="Tipos de Cama" />
                                                <div class="mt-2 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                                    <label class="flex items-center space-x-2">
                                                        <input type="checkbox" value="California King" v-model="form.bed_types" class="mr-2" />
                                                        <span class="text-sm font-medium text-gray-700">California King</span>
                                                    </label>
                                                    <label class="flex items-center space-x-2">
                                                        <input type="checkbox" value="Casal Padrão" v-model="form.bed_types" class="mr-2" />
                                                        <span class="text-sm font-medium text-gray-700">Casal Padrão</span>
                                                    </label>
                                                    <label class="flex items-center space-x-2">
                                                        <input type="checkbox" value="Double" v-model="form.bed_types" class="mr-2" />
                                                        <span class="text-sm font-medium text-gray-700">Double</span>
                                                    </label>
                                                    <label class="flex items-center space-x-2">
                                                        <input type="checkbox" value="European King" v-model="form.bed_types" class="mr-2" />
                                                        <span class="text-sm font-medium text-gray-700">European King</span>
                                                    </label>
                                                    <label class="flex items-center space-x-2">
                                                        <input type="checkbox" value="King Size" v-model="form.bed_types" class="mr-2" />
                                                        <span class="text-sm font-medium text-gray-700">King Size</span>
                                                    </label>
                                                    <label class="flex items-center space-x-2">
                                                        <input type="checkbox" value="Queen Size" v-model="form.bed_types" class="mr-2" />
                                                        <span class="text-sm font-medium text-gray-700">Queen Size</span>
                                                    </label>
                                                    <label class="flex items-center space-x-2">
                                                        <input type="checkbox" value="Super King" v-model="form.bed_types" class="mr-2" />
                                                        <span class="text-sm font-medium text-gray-700">Super King</span>
                                                    </label>
                                                    <label class="flex items-center space-x-2">
                                                        <input type="checkbox" value="Texas King" v-model="form.bed_types" class="mr-2" />
                                                        <span class="text-sm font-medium text-gray-700">Texas King</span>
                                                    </label>
                                                </div>
                                                <InputError class="mt-2" :message="form.errors.bed_types" />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Banheiro e Higiene -->
                                    <div class="bg-blue-50 rounded-lg p-6">
                                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            Banheiro e Higiene
                                        </h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                            <!-- Banheiro -->
                                            <div>
                                                <InputLabel for="bathroom" value="Banheiro" />
                                                <select
                                                    id="bathroom"
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    v-model="form.bathroom"
                                                >
                                                    <option v-for="n in 11" :key="n-1" :value="n-1">{{ n-1 }}</option>
                                                </select>
                                                <InputError class="mt-2" :message="form.errors.bathroom" />
                                            </div>

<!-- Hidromassagem -->
                                             <div>
                                                 <ToggleSwitchSimple 
                                                     id="hydromassage" 
                                                     label="Hidromassagem" 
                                                     v-model="form.hydromassage" 
                                                 />
                                                 <InputError class="mt-2" :message="form.errors.hydromassage" />
                                             </div>
                                        </div>
                                    </div>

                                    <!-- Climatização e Conforto -->
                                    <div class="bg-green-50 rounded-lg p-6">
                                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
                                            </svg>
                                            Climatização e Conforto
                                        </h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
<!-- Ar Condicionado -->
                                             <div>
                                                 <ToggleSwitchSimple 
                                                     id="air_conditioning" 
                                                     label="Ar Condicionado" 
                                                     v-model="form.air_conditioning" 
                                                 />
                                                 <InputError class="mt-2" :message="form.errors.air_conditioning" />
                                             </div>

<!-- Lareira -->
                                             <div>
                                                 <ToggleSwitchSimple 
                                                     id="fireplace" 
                                                     label="Lareira" 
                                                     v-model="form.fireplace" 
                                                 />
                                                 <InputError class="mt-2" :message="form.errors.fireplace" />
                                             </div>
                                        </div>
                                    </div>

                                    <!-- Cozinha e Refeições -->
                                    <div class="bg-yellow-50 rounded-lg p-6">
                                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                            Cozinha e Refeições
                                        </h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
<!-- Cooktop -->
                                             <div>
                                                 <ToggleSwitchSimple 
                                                     id="cooktop" 
                                                     label="Cooktop" 
                                                     v-model="form.cooktop" 
                                                 />
                                                 <InputError class="mt-2" :message="form.errors.cooktop" />
                                             </div>

<!-- Microondas -->
                                             <div>
                                                 <ToggleSwitchSimple 
                                                     id="microwave" 
                                                     label="Microondas" 
                                                     v-model="form.microwave" 
                                                 />
                                                 <InputError class="mt-2" :message="form.errors.microwave" />
                                             </div>

<!-- Geladeira -->
                                             <div>
                                                 <ToggleSwitchSimple 
                                                     id="refrigerator" 
                                                     label="Geladeira" 
                                                     v-model="form.refrigerator" 
                                                 />
                                                 <InputError class="mt-2" :message="form.errors.refrigerator" />
                                             </div>

<!-- Cafeteira -->
                                             <div>
                                                 <ToggleSwitchSimple 
                                                     id="coffee_maker" 
                                                     label="Cafeteira" 
                                                     v-model="form.coffee_maker" 
                                                 />
                                                 <InputError class="mt-2" :message="form.errors.coffee_maker" />
                                             </div>

<!-- Grill -->
                                             <div>
                                                 <ToggleSwitchSimple 
                                                     id="grill" 
                                                     label="Grill" 
                                                     v-model="form.grill" 
                                                 />
                                                 <InputError class="mt-2" :message="form.errors.grill" />
                                             </div>

<!-- Adega -->
                                             <div>
                                                 <ToggleSwitchSimple 
                                                     id="wine_cellar" 
                                                     label="Adega" 
                                                     v-model="form.wine_cellar" 
                                                 />
                                                 <InputError class="mt-2" :message="form.errors.wine_cellar" />
                                             </div>
                                        </div>
                                    </div>

                                    <!-- Lazer e Lazer Aquático -->
                                    <div class="bg-cyan-50 rounded-lg p-6">
                                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                            </svg>
                                            Lazer e Lazer Aquático
                                        </h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
<!-- Piscina -->
                                             <div>
                                                 <ToggleSwitchSimple 
                                                     id="pool" 
                                                     label="Piscina" 
                                                     v-model="form.pool" 
                                                 />
                                                 <InputError class="mt-2" :message="form.errors.pool" />
                                             </div>

<!-- Mini Piscina -->
                                             <div>
                                                 <ToggleSwitchSimple 
                                                     id="mini_pool" 
                                                     label="Mini Piscina" 
                                                     v-model="form.mini_pool" 
                                                 />
                                                 <InputError class="mt-2" :message="form.errors.mini_pool" />
                                             </div>
                                        </div>
                                    </div>

                                    <!-- Móveis e Estrutura -->
                                    <div class="bg-purple-50 rounded-lg p-6">
                                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                            Móveis e Estrutura
                                        </h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
<!-- Mezanino -->
                                             <div>
                                                 <ToggleSwitchSimple 
                                                     id="mezzanine" 
                                                     label="Mezanino" 
                                                     v-model="form.mezzanine" 
                                                 />
                                                 <InputError class="mt-2" :message="form.errors.mezzanine" />
                                             </div>
                                        </div>
                                    </div>

                                    <!-- Tecnologia e Conectividade -->
                                    <div class="bg-red-50 rounded-lg p-6">
                                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path>
                                            </svg>
                                            Tecnologia e Conectividade
                                        </h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
<!-- TV -->
                                             <div>
                                                 <ToggleSwitchSimple 
                                                     id="tv" 
                                                     label="TV" 
                                                     v-model="form.tv" 
                                                 />
                                                 <InputError class="mt-2" :message="form.errors.tv" />
                                             </div>

<!-- WiFi -->
                                             <div>
                                                 <ToggleSwitchSimple 
                                                     id="wifi" 
                                                     label="WiFi" 
                                                     v-model="form.wifi" 
                                                 />
                                                 <InputError class="mt-2" :message="form.errors.wifi" />
                                             </div>
                                        </div>
                                    </div>

                                    <!-- Serviços Inclusos -->
                                    <div class="bg-orange-50 rounded-lg p-6">
                                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Serviços Inclusos
                                        </h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
<!-- Café da manhã incluso -->
                                             <div>
                                                 <ToggleSwitchSimple 
                                                     id="breakfast_included" 
                                                     label="Café da manhã incluso" 
                                                     v-model="form.breakfast_included" 
                                                 />
                                                 <InputError class="mt-2" :message="form.errors.breakfast_included" />
                                             </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <Link :href="route('accommodations.index')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Cancelar
                                </Link>
                                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Salvar Alterações
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>