<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';

const form = useForm({
    name: '',
    start_date: '',
    end_date: '',
    priority: 0,
    active: true,
});

const submit = () => {
    form.post(route('seasons.store'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Nova Temporada" />

    <AppLayout title="Nova Temporada">


        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <!-- Name -->
                            <div>
                                <InputLabel for="name" value="Nome (ex: Alta Temporada 2026)" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    required
                                    autofocus
                                />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <!-- Start Date -->
                                <div>
                                    <InputLabel for="start_date" value="Data de Início" />
                                    <TextInput
                                        id="start_date"
                                        type="date"
                                        class="mt-1 block w-full"
                                        v-model="form.start_date"
                                        required
                                    />
                                    <InputError class="mt-2" :message="form.errors.start_date" />
                                </div>

                                <!-- End Date -->
                                <div>
                                    <InputLabel for="end_date" value="Data de Término" />
                                    <TextInput
                                        id="end_date"
                                        type="date"
                                        class="mt-1 block w-full"
                                        v-model="form.end_date"
                                        required
                                    />
                                    <InputError class="mt-2" :message="form.errors.end_date" />
                                </div>
                            </div>

                            <!-- Priority -->
                            <div class="mt-4">
                                <InputLabel for="priority" value="Prioridade (Quanto maior, mais prioritária em datas conflitantes)" />
                                <TextInput
                                    id="priority"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.priority"
                                    required
                                />
                                <p class="text-xs text-gray-500 mt-1">Ex: Feriado (10) tem prioridade sobre Alta Temporada (5).</p>
                                <InputError class="mt-2" :message="form.errors.priority" />
                            </div>

                            <!-- Active -->
                            <div class="block mt-4">
                                <label class="flex items-center">
                                    <Checkbox name="active" v-model:checked="form.active" />
                                    <span class="ms-2 text-sm text-gray-600">Ativa</span>
                                </label>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <Link
                                    :href="route('seasons.index')"
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
