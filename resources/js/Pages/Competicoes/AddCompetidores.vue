<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { submitForm, submitFormPut } from '@/config/usaForm';
import FormSelect from '@/Components/FormSelect.vue';
import AutoComplete from '@/Components/AutoComplete.vue';
import FormButton from '@/Components/FormButton.vue';
import { axiosGet } from '@/config/axiosConfig';
import { ref } from 'vue';
import NavLink from '@/Components/NavLink.vue';

const props = defineProps<{
    competicao: Competicao
}>();

interface CompeticaoCompetidor extends Record<string, any> {
    competidores: {
        position: number;
        competidor_id: number;
        categoria_id: string;
        faixa: string;
        categorias: Array<any>
    }[];
}

const form = useForm<CompeticaoCompetidor>({
    competidores: [],
});
const position = ref<number>(0);

function addCompetidor() {
    form.competidores.push({
        position: position.value,
        competidor_id: 0,
        categoria_id: '',
        faixa: '',
        categorias: [],
    });
    position.value++;
}

function removerCompetidor(index: number) {
    form.competidores.splice(index, 1);
    form.competidores = [...form.competidores];
}

async function formSubmit() {
    await submitForm(form, route('competicao.salvar-competidores', [props.competicao.id]));
}

async function getCategoria(index: number) {
    form.competidores[index].categorias = [];
    form.competidores[index].categoria_id = "";
    form.competidores[index].faixa = "";
    const response = await axiosGet(route('competidores.get-categorias-competidor', form.competidores[index].competidor_id), {});
    form.competidores[index].categorias = response['categorias'];
    form.competidores[index].faixa = response['faixa'];
    if (response['categorias'].length == 1) {
        form.competidores[index].categoria_id = response['categorias'][0]['id'];
    }
}

</script>

<template>

    <Head title="Competicao" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                <p>Competição #{{ props.competicao.id }} {{ props.competicao.descricao }}</p>
                Adicionando Competidores
            </h2>
        </template>

        <div class="bg-white shadow dark:bg-gray-800 mt-3 mr-auto ml-auto w-4/5 mx-auto p-5">
            <form @submit.prevent="formSubmit">
                <FormButton type="button"
                    class="mr-1 items-center rounded-md border border-transparent bg-blue-300 px-2 py-1 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-blue-300 focus:bg-blue-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-stone-950 dark:bg-blue-300 dark:text-stone-950 dark:hover:bg-white dark:focus:bg-white dark:focus:ring-offset-blue-300 dark:active:bg-blue-300"
                    sugestion="Adicionar competidor." @click="addCompetidor">
                    <font-awesome-icon :icon="['fas', 'plus']" />
                </FormButton>
                <template v-for="(competidor, index) in form.competidores" :key="competidor.position">
                    <div class="mt-2">
                        <div class="mb-2">
                            <FormButton type="button"
                                class="mr-1 items-center rounded-md border border-transparent bg-red-300 px-2 py-1 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-red-300 focus:bg-red-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-stone-950 dark:bg-red-300 dark:text-stone-950 dark:hover:bg-white dark:focus:bg-white dark:focus:ring-offset-red-300 dark:active:bg-red-300"
                                sugestion="Remover competidor." @click="removerCompetidor(index)">
                                <font-awesome-icon :icon="['fas', 'minus']" />
                            </FormButton>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="">
                                <AutoComplete :apiUrl="route('competidores.get-buscar-competidores')" labelKey="nome"
                                    v-model="competidor.competidor_id" :id="`competidor.${index}.competidor_id`" clearable
                                    :minInput="2" :params="{
                                        competicao: props.competicao.id
                                    }" placeHolderProps="Pesquise o competidor por nome ou id" :required="true"
                                    @update:modelValue="getCategoria(index)">
                                    Competidor
                                </AutoComplete>
                            </div>
                            <div class="">
                                <FormSelect label="Categoria" :id="`competidor.${index}.categoria`"
                                    :name="`competidor.${index}.categoria`" :options="competidor.categorias"
                                    v-model="competidor.categoria_id" :required="true">
                                </FormSelect>
                            </div>
                        </div>
                    </div>
                </template>

                <div class="flex justify-end">
                    <div class="ml-auto mt-2 inline-flex">
                        <NavLink :href="route('competicao.lista-competidores', [props.competicao.id])"
                            class="mr-1 items-center rounded-md border border-transparent bg-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-300 focus:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-stone-950 dark:bg-gray-300 dark:text-stone-950 dark:hover:bg-white dark:focus:bg-white dark:focus:ring-offset-gray-300 dark:active:bg-gray-300"
                            :button="true">
                            Voltar
                        </NavLink>
                        <FormButton type="submit"
                            class="mr-1 items-center rounded-md border border-transparent bg-green-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-green-300 focus:bg-green-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-stone-950 dark:bg-green-300 dark:text-stone-950 dark:hover:bg-white dark:focus:bg-white dark:focus:ring-offset-green-300 dark:active:bg-green-300"
                            :disabled="form.processing">
                            Salvar
                        </FormButton>
                    </div>
                </div>
            </form>
        </div>

    </AuthenticatedLayout>
</template>
