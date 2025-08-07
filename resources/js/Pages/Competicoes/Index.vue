<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { IPagination } from '@/types/ipagination';
import NavLink from '@/Components/NavLink.vue';
import FormButton from '@/Components/FormButton.vue';
import { onDeleteItem, searchForm } from "@/config/usaForm.js";
import FormInput from '@/Components/FormInput.vue';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faPersonCirclePlus } from '@fortawesome/free-solid-svg-icons';
import helperData from "@/helpers/datehelper"

library.add(faPersonCirclePlus);

const props = defineProps<{
    competicoes: IPagination,
    filtros: {
        search: string
    }
}>();

const formSearch = useForm({
    search: props.filtros?.search ?? ""
});

async function sendSearch()
{
    await searchForm(formSearch, route('competicoes.index'));
}

</script>

<template>
    <Head title="competicoes" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
                Competicões
            </h2>
        </template>

        <div class="bg-white dark:bg-gray-900 shadow-md mt-6 w-11/12 mx-auto rounded-xl overflow-hidden">
            <div>
                <form @submit.prevent="sendSearch()">
                    <FormInput
                        type="text"
                        placeholder="Pesquise pelo descrição"
                        id="search"
                        name="search"
                        v-model="formSearch.search"
                    >
                    </FormInput>
                    <div class="flex justify-end p-4">
                        <FormButton
                            type="submit"
                            class="inline-flex mr-1 items-center rounded-md border border-transparent bg-purple-600 px-4 py-2 text-xs font-bold uppercase tracking-wider dark:text-white text-white transition hover:bg-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-400 dark:bg-purple-500 dark:hover:bg-purple-400"
                        >
                            <font-awesome-icon :icon="['fas', 'magnifying-glass']" />
                        </FormButton>

                        <NavLink
                            class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-xs font-bold uppercase tracking-wider dark:text-white text-white transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-400 dark:bg-indigo-500 dark:hover:bg-indigo-400"
                            :href="route('competicoes.create')"
                        >
                            Novo
                        </NavLink>
                    </div>
                </form>
            </div>

            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Descrição</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Data do evento</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    <template v-for="competicao in props.competicoes.data" :key="competicao.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ competicao.id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ competicao.descricao }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ helperData.formatarData(competicao.data_evento) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                <NavLink
                                    :href="route('competicoes.edit', [competicao])"
                                    class="mr-1 items-center rounded-md border border-transparent bg-gray-300 px-2 py-1 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-300 focus:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-stone-950 dark:bg-gray-300 dark:text-stone-950 dark:hover:bg-white dark:focus:bg-white dark:focus:ring-offset-gray-300 dark:active:bg-gray-300"
                                    :button="true"
                                    sugestion="Editar competição"
                                >
                                    <font-awesome-icon :icon="['fas', 'pen-to-square']" />
                                </NavLink>
                                <FormButton
                                    type="button"
                                    class="mr-1 items-center rounded-md border border-transparent bg-red-300 px-2 py-1 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-red-300 focus:bg-red-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-stone-950 dark:bg-red-300 dark:text-stone-950 dark:hover:bg-white dark:focus:bg-white dark:focus:ring-offset-red-300 dark:active:bg-red-300"
                                    @click="onDeleteItem(route('competicoes.destroy', competicao.id))"
                                    sugestion="Excluir competição"
                                >
                                    <font-awesome-icon :icon="['fas', 'trash']" />
                                </FormButton>
                                <NavLink
                                    :href="route('competicao.lista-competidores', [competicao])"
                                    class="mr-1 items-center rounded-md border border-transparent bg-gray-300 px-2 py-1 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-300 focus:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-stone-950 dark:bg-gray-300 dark:text-stone-950 dark:hover:bg-white dark:focus:bg-white dark:focus:ring-offset-gray-300 dark:active:bg-gray-300"
                                    :button="true"
                                    sugestion="Adicionar competidores"
                                >
                                    <font-awesome-icon :icon="['fas', 'person-circle-plus']" />
                                </NavLink>
                                <NavLink
                                    v-if="competicao.competidores.length > 0"
                                    :href="route('competicao.gerar-tabela-competicao', [competicao])"
                                    class="mr-1 items-center rounded-md border border-transparent bg-gray-300 px-2 py-1 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-300 focus:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-stone-950 dark:bg-gray-300 dark:text-stone-950 dark:hover:bg-white dark:focus:bg-white dark:focus:ring-offset-gray-300 dark:active:bg-gray-300"
                                    :button="true"
                                    :sugestion="(competicao.chave_gerada) ? 'Lista de chaveamento' : 'Gerar chaveamento de competidores'"
                                >
                                    <font-awesome-icon :icon="['fas', 'clipboard-list']" />
                                </NavLink>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>

            <div class="p-4">
                <Pagination :pagination="props.competicoes" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
