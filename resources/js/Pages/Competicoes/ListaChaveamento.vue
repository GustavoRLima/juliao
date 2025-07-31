<script setup lang="ts">
import FormButton from '@/Components/FormButton.vue';
import NavLink from '@/Components/NavLink.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps<{
    competicao: Competicao,
    categorias: {
        id: number,
        nome: string,
        faixa: string,
        qtd_competidores: number
    }[]
}>();

function gerarNovoChaveamento(){
    
}

</script>

<template>
    <Head title="competicoes" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
                Chaveamento competição #{{props.competicao.id}} {{ props.competicao.descricao }}
            </h2>
        </template>

        <div class="bg-white dark:bg-gray-900 shadow-md mt-6 w-11/12 mx-auto rounded-xl overflow-hidden">
            <FormButton
                type="button"
                :click="gerarNovoChaveamento()"
                class="inline-flex mr-1 items-center rounded-md border border-transparent bg-purple-600 px-4 py-2 text-xs font-bold uppercase tracking-wider dark:text-white text-white transition hover:bg-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-400 dark:bg-purple-500 dark:hover:bg-purple-400"
            >
                Gerar novo chaveamento
            </FormButton>

            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Categoria</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Faixa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Qtd competidores</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    <template v-for="categoria in props.categorias" :key="categoria.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ categoria.nome }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ categoria.faixa }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ categoria.qtd_competidores }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                <NavLink
                                    :href="route('competicao.ver-tabela-competicao', [competicao, categoria.id, categoria.faixa])"
                                    class="mr-1 items-center rounded-md border border-transparent bg-gray-300 px-2 py-1 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-300 focus:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-stone-950 dark:bg-gray-300 dark:text-stone-950 dark:hover:bg-white dark:focus:bg-white dark:focus:ring-offset-gray-300 dark:active:bg-gray-300"
                                    :button="true"
                                    sugestion="Ver tabela"
                                >
                                    <font-awesome-icon :icon="['fas', 'clipboard-list']" />
                                </NavLink>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>
</template>
