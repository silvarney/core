<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    seasons: Array,
    accommodationTypes: Array,
});

const form = useForm({
    accommodation_type_id: '',
    season_id: '',
    price: '',
    min_nights: 1,
});

const submit = () => {
    form.post(route('rates.store'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Nova Tarifa" />

    <AppLayout title="Nova Tarifa">


        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Accommodation Type -->
                                <div>
                                    <InputLabel for="accommodation_type_id" value="Tipo de Acomodação" />
                                    <select
                                        id="accommodation_type_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        v-model="form.accommodation_type_id"
                                        required
                                    >
                                        <option value="" disabled>Selecione um tipo</option>
                                        <option v-for="type in accommodationTypes" :key="type.id" :value="type.id">
                                            {{ type.name }} ({{ type.property?.name }})
                                        </option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.accommodation_type_id" />
                                </div>

                                <!-- Season -->
                                <div>
                                    <InputLabel for="season_id" value="Temporada" />
                                    <select
                                        id="season_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        v-model="form.season_id"
                                        required
                                    >
                                        <option value="" disabled>Selecione uma temporada</option>
                                        <option v-for="season in seasons" :key="season.id" :value="season.id">
                                            {{ season.name }}
                                        </option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.season_id" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <!-- Price -->
                                <div>
                                    <InputLabel for="price" value="Preço da Diária" />
                                    <TextInput
                                        id="price"
                                        type="number"
                                        step="0.01"
                                        class="mt-1 block w-full"
                                        v-model="form.price"
                                        required
                                    />
                                    <InputError class="mt-2" :message="form.errors.price" />
                                </div>

                                <!-- Min Nights -->
                                <div>
                                    <InputLabel for="min_nights" value="Mínimo de Noites" />
                                    <TextInput
                                        id="min_nights"
                                        type="number"
                                        class="mt-1 block w-full"
                                        v-model="form.min_nights"
                                        required
                                        min="1"
                                    />
                                    <InputError class="mt-2" :message="form.errors.min_nights" />
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <Link
                                    :href="route('rates.index')"
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
