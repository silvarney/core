<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    properties: Array,
});

const form = useForm({
    property_id: '',
    name: '',
    description: '',
    capacity_adults: 2,
    capacity_children: 0,
    size_m2: '',
    base_price: '',
    checkin_time: '14:00',
    checkout_time: '11:00',
});

const submit = () => {
    form.post(route('accommodation-types.store'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Novo Tipo de Acomodação" />

    <AppLayout title="Novo Tipo de Acomodação">


        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <!-- Property -->
                            <div>
                                <InputLabel for="property_id" value="Propriedade" />
                                <select
                                    id="property_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    v-model="form.property_id"
                                    required
                                >
                                    <option value="" disabled>Selecione uma propriedade</option>
                                    <option v-for="property in properties" :key="property.id" :value="property.id">
                                        {{ property.name }}
                                    </option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.property_id" />
                            </div>

                            <!-- Name -->
                            <div class="mt-4">
                                <InputLabel for="name" value="Nome (ex: Suíte Master)" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <!-- Description -->
                            <div class="mt-4">
                                <InputLabel for="description" value="Descrição" />
                                <textarea
                                    id="description"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    v-model="form.description"
                                    rows="3"
                                ></textarea>
                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                <!-- Capacity Adults -->
                                <div>
                                    <InputLabel for="capacity_adults" value="Adultos" />
                                    <TextInput
                                        id="capacity_adults"
                                        type="number"
                                        class="mt-1 block w-full"
                                        v-model="form.capacity_adults"
                                        min="0"
                                        required
                                    />
                                    <InputError class="mt-2" :message="form.errors.capacity_adults" />
                                </div>

                                <!-- Capacity Children -->
                                <div>
                                    <InputLabel for="capacity_children" value="Crianças" />
                                    <TextInput
                                        id="capacity_children"
                                        type="number"
                                        class="mt-1 block w-full"
                                        v-model="form.capacity_children"
                                        min="0"
                                        required
                                    />
                                    <InputError class="mt-2" :message="form.errors.capacity_children" />
                                </div>

                                <!-- Size m2 -->
                                <div>
                                    <InputLabel for="size_m2" value="Tamanho (m²)" />
                                    <TextInput
                                        id="size_m2"
                                        type="number"
                                        class="mt-1 block w-full"
                                        v-model="form.size_m2"
                                        min="0"
                                    />
                                    <InputError class="mt-2" :message="form.errors.size_m2" />
                                </div>
                            </div>

                            <!-- Base Price -->
                            <div class="mt-4">
                                <InputLabel for="base_price" value="Preço Base (Diária)" />
                                <TextInput
                                    id="base_price"
                                    type="number"
                                    step="0.01"
                                    class="mt-1 block w-full"
                                    v-model="form.base_price"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.base_price" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <!-- Check-in Time -->
                                <div>
                                    <InputLabel for="checkin_time" value="Horário de Check-in" />
                                    <input
                                        id="checkin_time"
                                        type="time"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        v-model="form.checkin_time"
                                        required
                                    />
                                    <InputError class="mt-2" :message="form.errors.checkin_time" />
                                </div>

                                <!-- Check-out Time -->
                                <div>
                                    <InputLabel for="checkout_time" value="Horário de Check-out" />
                                    <input
                                        id="checkout_time"
                                        type="time"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        v-model="form.checkout_time"
                                        required
                                    />
                                    <InputError class="mt-2" :message="form.errors.checkout_time" />
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <Link
                                    :href="route('accommodation-types.index')"
                                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    Cancelar
                                </Link>

                                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Salvar
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>

</template>
