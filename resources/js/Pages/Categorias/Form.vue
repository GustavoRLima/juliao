<script setup lang="ts">
import FormInput from '@/Components/FormInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { submitForm, submitFormPut } from '@/config/usaForm';
import FormButton from '@/Components/FormButton.vue';
import NavLink from '@/Components/NavLink.vue';
import FormRadio from '@/Components/FormRadio.vue';

const props = defineProps<{
    categoria: Categoria,
}>();

const form = useForm({
    nome: props.categoria?.nome ?? '',
    sexo: props.categoria?.sexo ?? 1,
    peso_inicio: props.categoria?.peso_inicio ?? 0,
    peso_fim: props.categoria?.peso_fim ?? 0,
    idade_inicio: props.categoria?.idade_inicio ?? 0,
    idade_fim: props.categoria?.idade_fim ?? 0,
});

async function formSubmit() {
    if (props.categoria) {
        await submitFormPut(form, route('categorias.update', [props.categoria.id]));
    } else {
        await submitForm(form, route('categorias.store'));
    }
}

</script>

<template>

    <Head title="categorias" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Novo categoria
            </h2>
        </template>

        <div class="bg-white shadow dark:bg-gray-800 mt-3 mr-auto ml-auto w-4/5 mx-auto p-5">
            <form @submit.prevent="formSubmit">
                <div class="row">
                    <div class="col-12">
                        <FormInput type="text" label="Nome" id="nome" name="nome" v-model="form.nome" :required="true">
                        </FormInput>
                    </div>
                    <div class="col-6">
                        <FormInput type="number" step="1" label="idade inicial" id="idade_inicio" name="idade_inicio"
                            v-model="form.idade_inicio" :required="true">
                        </FormInput>
                    </div>
                    <div class="col-6">
                        <FormInput type="number" step="1" label="idade final" id="idade_fim" name="idade_fim"
                            v-model="form.idade_fim" :required="true">
                        </FormInput>
                    </div>
                    <div class="col-6">
                        <FormInput type="number" step="0.001" label="Peso inicial" id="peso_inicio" name="peso_inicio"
                            v-model="form.peso_inicio" :required="true">
                        </FormInput>
                    </div>
                    <div class="col-6">
                        <FormInput type="number" step="0.001" label="Peso final" id="peso_fim" name="peso_fim" v-model="form.peso_fim"
                            :required="true">
                        </FormInput>
                    </div>
                    <div class="col-12">
                        <FormRadio 
                            id="sexo" 
                            name="sexo" 
                            label="Sexo" 
                            :options="[
                                { value: '1', label: 'Ambos' },
                                { value: '2', label: 'Masculino' },
                                { value: '3', label: 'Feminino' }
                            ]" 
                            v-model="form.sexo" 
                            :required="true" 
                        />
                    </div>
                </div>

                <div class="flex justify-end">
                    <div class="ml-auto mt-2 inline-flex">
                        <NavLink :href="route('categorias.index')"
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
