<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { computed, watch } from 'vue';
import axios from 'axios';

const props = defineProps({
    booking: Object,
    accommodations: Array,
    users: Array,
});

const form = useForm({
    user_id: props.booking.user_id,
    accommodation_id: props.booking.accommodation_id,
    check_in: props.booking.check_in || '',
    check_out: props.booking.check_out || '',
    guests_adults: props.booking.guests_adults,
    guests_children: props.booking.guests_children,
    total_price: props.booking.total_price,
    status: props.booking.status,
    payment_status: props.booking.payment_status,
    notes: props.booking.notes,
});

const selectedAccommodation = computed(() => {
    return props.accommodations.find(acc => acc.id === form.accommodation_id);
});

const maxAdults = computed(() => selectedAccommodation.value?.type?.capacity_adults ?? 99);
const maxChildren = computed(() => selectedAccommodation.value?.type?.capacity_children ?? 99);

const calculatePrice = async () => {
    if (form.accommodation_id && form.check_in && form.check_out) {
        if (!form.check_in || !form.check_out) return;
        if (new Date(form.check_in) >= new Date(form.check_out)) {
            form.total_price = '';
            return;
        }
        
        try {
            const response = await axios.post(route('bookings.calculate-price'), {
                accommodation_id: form.accommodation_id,
                check_in: form.check_in,
                check_out: form.check_out,
            });
            form.total_price = response.data.price;
        } catch (error) {
            console.error('Error calculating price:', error);
            form.total_price = '';
        }
    }
};

// Validação de datas cross-field
const validateDates = () => {
    if (form.check_in && form.check_out) {
        const checkInDate = new Date(form.check_in);
        const checkOutDate = new Date(form.check_out);
        
        // Limpa erros anteriores
        delete form.errors.check_in;
        delete form.errors.check_out;
        
        if (checkInDate >= checkOutDate) {
            form.setError('check_out', 'Check-out deve ser maior que Check-in');
            return false;
        }
    }
    return true;
};

watch(() => form.accommodation_id, (newVal, oldVal) => {
    if (newVal && newVal !== oldVal) {
        if (form.guests_adults > maxAdults.value) {
            form.guests_adults = maxAdults.value;
        }
        if (form.guests_children > maxChildren.value) {
            form.guests_children = maxChildren.value;
        }
        calculatePrice();
    }
});

// Watch individual para check-in e check-out
watch(() => form.check_in, (newValue, oldValue) => {
    if (newValue && oldValue && newValue !== oldValue) {
        // Se check-in mudar, ajusta o mínimo do check-out
        const nextDay = new Date(newValue);
        nextDay.setDate(nextDay.getDate() + 1);
        
        // Se check-out atual for menor ou igual ao novo check-in, ajusta automaticamente
        if (form.check_out && new Date(form.check_out) <= new Date(newValue)) {
            form.check_out = nextDay.toISOString().split('T')[0];
        }
    }
    validateDates();
    calculatePrice();
});

watch(() => form.check_out, () => {
    validateDates();
    calculatePrice();
});

const submit = () => {
    // Validação final antes de submeter
    if (!validateDates()) {
        return;
    }
    
    form.put(route('bookings.update', props.booking.id), {
        onFinish: () => {},
    });
};
</script>

<template>
    <Head title="Editar Reserva" />

    <AppLayout title="Editar Reserva">


        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- User -->
                                <div>
                                    <InputLabel for="user_id" value="Cliente" />
                                    <select
                                        id="user_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        v-model="form.user_id"
                                        required
                                    >
                                        <option v-for="user in users" :key="user.id" :value="user.id">
                                            {{ user.name }} ({{ user.email }})
                                        </option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.user_id" />
                                </div>

                                <!-- Accommodation -->
                                <div>
                                    <InputLabel for="accommodation_id" value="Acomodação / Unidade" />
                                    <select
                                        id="accommodation_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        v-model="form.accommodation_id"
                                        required
                                    >
                                        <option v-for="acc in accommodations" :key="acc.id" :value="acc.id">
                                            {{ acc.name }} - {{ acc.type?.name }} ({{ acc.type?.property?.name }})
                                        </option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.accommodation_id" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <!-- Check-in -->
                                <div>
                                    <InputLabel for="check_in" value="Check-in" />
                                    <TextInput
                                        id="check_in"
                                        type="date"
                                        class="mt-1 block w-full"
                                        v-model="form.check_in"
                                        :min="new Date().toISOString().split('T')[0]"
                                        required
                                    />
                                    <InputError class="mt-2" :message="form.errors.check_in" />
                                </div>

                                <!-- Check-out -->
                                <div>
                                    <InputLabel for="check_out" value="Check-out" />
                                    <TextInput
                                        id="check_out"
                                        type="date"
                                        class="mt-1 block w-full"
                                        :class="{ 'border-red-500': form.errors.check_out }"
                                        v-model="form.check_out"
                                        :min="form.check_in ? new Date(new Date(form.check_in).getTime() + 24 * 60 * 60 * 1000).toISOString().split('T')[0] : new Date().toISOString().split('T')[0]"
                                        required
                                    />
                                    <InputError class="mt-2" :message="form.errors.check_out" />
                                    <p v-if="form.check_in && !form.check_out" class="text-xs text-gray-500 mt-1">
                                        Check-out deve ser posterior ao Check-in
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                <!-- Adults -->
                                <div>
                                    <InputLabel for="guests_adults" value="Adultos" />
                                    <TextInput
                                        id="guests_adults"
                                        type="number"
                                        class="mt-1 block w-full"
                                        v-model="form.guests_adults"
                                        required
                                        min="1"
                                        :max="maxAdults"
                                    />
                                    <p v-if="selectedAccommodation" class="text-[10px] text-gray-500 mt-1">Máx: {{ maxAdults }} adultos</p>
                                    <InputError class="mt-2" :message="form.errors.guests_adults" />
                                </div>

                                <!-- Children -->
                                <div>
                                    <InputLabel for="guests_children" value="Crianças" />
                                    <TextInput
                                        id="guests_children"
                                        type="number"
                                        class="mt-1 block w-full"
                                        v-model="form.guests_children"
                                        required
                                        min="0"
                                        :max="maxChildren"
                                    />
                                    <p v-if="selectedAccommodation" class="text-[10px] text-gray-500 mt-1">Máx: {{ maxChildren }} crianças</p>
                                    <InputError class="mt-2" :message="form.errors.guests_children" />
                                </div>

                                <!-- Total Price -->
                                <div>
                                    <InputLabel for="total_price" value="Preço Total" />
                                    <TextInput
                                        id="total_price"
                                        type="number"
                                        step="0.01"
                                        class="mt-1 block w-full"
                                        v-model="form.total_price"
                                        required
                                    />
                                    <InputError class="mt-2" :message="form.errors.total_price" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <!-- Status -->
                                <div>
                                    <InputLabel for="status" value="Status da Reserva" />
                                    <select
                                        id="status"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        v-model="form.status"
                                        required
                                    >
                                        <option value="pending">Pendente</option>
                                        <option value="confirmed">Confirmada</option>
                                        <option value="cancelled">Cancelada</option>
                                        <option value="completed">Concluída</option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.status" />
                                </div>

                                <!-- Payment Status -->
                                <div>
                                    <InputLabel for="payment_status" value="Status do Pagamento" />
                                    <select
                                        id="payment_status"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        v-model="form.payment_status"
                                        required
                                    >
                                        <option value="unpaid">Não Pago</option>
                                        <option value="partial">Parcial</option>
                                        <option value="paid">Pago</option>
                                        <option value="refunded">Reembolsado</option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.payment_status" />
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="mt-4">
                                <InputLabel for="notes" value="Observações" />
                                <textarea
                                    id="notes"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    v-model="form.notes"
                                    rows="3"
                                ></textarea>
                                <InputError class="mt-2" :message="form.errors.notes" />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <Link
                                    :href="route('bookings.index')"
                                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
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
