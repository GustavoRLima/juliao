<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { onDeleteItem, searchForm } from '@/config/usaForm';
import { IPagination } from '@/types/ipagination';
import Pagination from '@/Components/Pagination.vue';
import FormButton from '@/Components/FormButton.vue';
import FormInput from '@/Components/FormInput.vue';
import NavLink from '@/Components/NavLink.vue';

const props = defineProps<{
    competidores: IPagination,
    filtros: {
        search: string
    }
    competicao: Competicao
}>();

const formSearch = useForm({
    search: props.filtros?.search ?? ""
});

async function sendSearch() {
    await searchForm(formSearch, route('competicao.lista-competidores', [props.competicao]));
}

</script>

<template>

    <Head title="Competicao" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                <p>Competição #{{ props.competicao.id }} {{ props.competicao.descricao }}</p>
                Competidores
            </h2>
        </template>


        <div class="bg-white dark:bg-gray-900 shadow-md mt-6 w-11/12 mx-auto rounded-xl overflow-hidden">
            <div>
                <form @submit.prevent="sendSearch()">
                    <FormInput type="text" placeholder="Pesquise pelo nome" id="search" name="search"
                        v-model="formSearch.search">
                    </FormInput>
                    <div class="flex justify-end p-4">
                        <FormButton type="submit"
                            class="inline-flex mr-1 items-center rounded-md border border-transparent bg-purple-600 px-4 py-2 text-xs font-bold uppercase tracking-wider dark:text-white text-white transition hover:bg-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-400 dark:bg-purple-500 dark:hover:bg-purple-400">
                            <font-awesome-icon :icon="['fas', 'magnifying-glass']" />
                        </FormButton>

                        <NavLink
                            class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-xs font-bold uppercase tracking-wider dark:text-white text-white transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-400 dark:bg-indigo-500 dark:hover:bg-indigo-400"
                            :href="route('competicao.add-competidores', [props.competicao])">
                            Adicionar competidor
                        </NavLink>
                    </div>
                </form>
            </div>

            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-800">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            #</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Nome</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Categoria</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Faixa</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    <template v-for="competidor in props.competidores.data" :key="competidor.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ competidor.competidor_id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ competidor.nome
                            }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ competidor.categoria_nome }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ competidor.faixa_name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                <FormButton type="button"
                                    class="mr-1 items-center rounded-md border border-transparent bg-red-300 px-2 py-1 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-red-300 focus:bg-red-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-stone-950 dark:bg-red-300 dark:text-stone-950 dark:hover:bg-white dark:focus:bg-white dark:focus:ring-offset-red-300 dark:active:bg-red-300"
                                    @click="onDeleteItem(route('competicao.excluir-competidores', {competicao: props.competicao, competidor: competidor.competidor_id, categoria: competidor.categoria_id}))"
                                    sugestion="Remover competidor">
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