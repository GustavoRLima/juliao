<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head } from '@inertiajs/vue3';
import { IPagination } from '@/types/ipagination';
import NavLink from '@/Components/NavLink.vue';
import FormButton from '@/Components/FormButton.vue';
import { onDeleteItem } from "@/config/usaForm.js";

const props = defineProps<{
    competidores: IPagination
}>();
</script>

<template>
    <Head title="Competidores" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
                Competidores
            </h2>
        </template>

        <div class="bg-white dark:bg-gray-900 shadow-md mt-6 w-11/12 mx-auto rounded-xl overflow-hidden">
            <div class="flex justify-end p-4">
                <NavLink
                    class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-xs font-bold uppercase tracking-wider dark:text-white text-white transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-400 dark:bg-indigo-500 dark:hover:bg-indigo-400"
                    :href="route('competidores.create')"
                >
                    Novo
                </NavLink>
            </div>

            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Nome</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Idade</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Peso</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Faixa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    <template v-for="competidor in props.competidores.data" :key="competidor.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ competidor.id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ competidor.nome }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ competidor.idade }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ competidor.peso }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ competidor.faixa }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                <NavLink
                                    :href="route('competidores.edit', [competidor])"
                                    class="mr-1 items-center rounded-md border border-transparent bg-gray-300 px-2 py-1 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-300 focus:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-stone-950 dark:bg-gray-300 dark:text-stone-950 dark:hover:bg-white dark:focus:bg-white dark:focus:ring-offset-gray-300 dark:active:bg-gray-300"
                                    :button="true"
                                >
                                    <font-awesome-icon :icon="['fas', 'pen-to-square']" />
                                </NavLink>
                                <FormButton
                                    type="button"
                                    class="mr-1 items-center rounded-md border border-transparent bg-red-300 px-2 py-1 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-red-300 focus:bg-red-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-stone-950 dark:bg-red-300 dark:text-stone-950 dark:hover:bg-white dark:focus:bg-white dark:focus:ring-offset-red-300 dark:active:bg-red-300"
                                    @click="onDeleteItem(route('competidores.destroy', competidor.id))"
                                >
                                    <font-awesome-icon :icon="['fas', 'trash']" />
                                </FormButton>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>

            <div class="p-4">
                <Pagination :pagination="props.competidores" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
