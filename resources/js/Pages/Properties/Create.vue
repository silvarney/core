<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';

const form = useForm({
    name: '',
    description: '',
    address: '',
    email: '',
    phone: '',
    active: true,
    site: '',
    api_key: '',
});

const generateApiKey = () => {
    const apiKey = 'sg_' + Math.random().toString(36).substr(2, 9) + '_' + Date.now().toString(36);
    form.api_key = apiKey;
};

const submit = () => {
    form.post(route('properties.store'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Nova Propriedade" />

    <AppLayout title="Nova Propriedade">


        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <!-- Name -->
                            <div>
                                <InputLabel for="name" value="Nome" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    required
                                    autofocus
                                    autocomplete="name"
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

                            <!-- Address -->
                            <div class="mt-4">
                                <InputLabel for="address" value="Endereço" />
                                <TextInput
                                    id="address"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.address"
                                />
                                <InputError class="mt-2" :message="form.errors.address" />
                            </div>

                            <!-- Email -->
                            <div class="mt-4">
                                <InputLabel for="email" value="E-mail de Contato" />
                                <TextInput
                                    id="email"
                                    type="email"
                                    class="mt-1 block w-full"
                                    v-model="form.email"
                                />
                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>

                            <!-- Phone -->
                            <div class="mt-4">
                                <InputLabel for="phone" value="Telefone" />
                                <TextInput
                                    id="phone"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.phone"
                                />
                                <InputError class="mt-2" :message="form.errors.phone" />
                            </div>

                            <!-- Site -->
                            <div class="mt-4">
                                <InputLabel for="site" value="Site/Domínio" />
                                <TextInput
                                    id="site"
                                    type="url"
                                    class="mt-1 block w-full"
                                    v-model="form.site"
                                    placeholder="https://exemplo.com"
                                />
                                <InputError class="mt-2" :message="form.errors.site" />
                            </div>

                            <!-- API Key -->
                            <div class="mt-4">
                                <InputLabel for="api_key" value="Chave API" />
                                <div class="flex gap-2">
                                    <TextInput
                                        id="api_key"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.api_key"
                                        placeholder="API key para consumo dos dados"
                                    />
                                    <SecondaryButton 
                                        type="button" 
                                        @click="generateApiKey"
                                        class="mt-1"
                                    >
                                        Gerar
                                    </SecondaryButton>
                                </div>
                                <InputError class="mt-2" :message="form.errors.api_key" />
                            </div>

                            <!-- Active -->
                            <div class="block mt-4">
                                <label class="flex items-center">
                                    <Checkbox name="active" v-model:checked="form.active" />
                                    <span class="ms-2 text-sm text-gray-600">Ativo</span>
                                </label>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <Link
                                    :href="route('properties.index')"
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
