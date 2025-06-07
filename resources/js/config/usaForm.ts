import { useLoadingStore } from '@/stores/loading';
import { useForm, usePage } from '@inertiajs/vue3';
import { useErrorStore } from '@/stores/errorShow';
import Swal from 'sweetalert2';

const page = usePage<PageProps>()

const formUse = useForm({});

export async function searchForm(form: any, route: string) {
    const loading = useLoadingStore();
    loading.start();
    const response = await form.get(route, {
        preserveScroll: true,
        onSuccess: () => {
            loading.stop();
        },
        onError: (errors: any) => {
            console.log('Erros de validação:', errors.detalhes);
            console.log(errors);
            loading.stop();
        }
    });
        
    return response;
}

export async function submitForm(form: any, route: string) {
    const loading = useLoadingStore();
    loading.start();
    const response = await form.post(route, {
        preserveScroll: true,
        onSuccess: () => {
            const message = page.props.flash?.message?.success 
            swalFireAlert('success', 'Sucesso', message ?? 'Operação realizada com sucesso!');
            loading.stop();
        },
        onError: (errors: any) => {
            console.log('Erros de validação:', errors.detalhes);
            console.log(errors);
            addErrorShow(errors)
            if(errors.error){
                swalFireAlert('error', 'Erro', errors.error);
            }else{
                swalFireAlert('error', 'Erro de Validação', 'Alguns campos não foram preenchidos corretamente.');
            }
            loading.stop();
        }
    });
        
    return response;
}

export async function submitFormPut(form: any, route: string) {
    const loading = useLoadingStore();
    loading.start();
    const response = await form.put(route, {
        preserveScroll: true,
        onSuccess: () => {
            const message = page.props.flash?.message?.success;

            swalFireAlert('success', 'Sucesso', message ?? 'Operação realizada com sucesso!');
            loading.stop();
        },
        onError: (errors: any) => {
            console.log('Erros de validação:', errors.detalhes);
            console.log(errors);
            addErrorShow(errors)
            if(errors.error){
                swalFireAlert('error', 'Erro', errors.error);
            }else{
                swalFireAlert('error', 'Erro de Validação', 'Alguns campos não foram preenchidos corretamente.');
            }
            loading.stop();
        }
    });
        
    return response;
}

function swalFireAlert(icon: any, title: string, text: string) {
    Swal.fire({
        icon: icon,
        title: title,
        text: text,
        position: "top-end",
        toast: true,
        timer: 5000,
        showConfirmButton: false
    });
}

export async function onDeleteItem(route: any, message: string = "Tem certeza que deseja excluir este registro?") {
    const loading = useLoadingStore();

    const swalReturn = await Swal.fire({
        title: 'Você tem certeza?',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    });

    if(!swalReturn.isConfirmed) return;
    loading.start();

    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams){
        route += '?' + urlParams.toString();
    }
    
    const response = await formUse.delete(route, {
        preserveScroll: true,
        onSuccess: () => {
            const message = page.props.flash?.message?.success;
            swalFireAlert('success', 'Sucesso', message ?? 'Exclusão realizada com sucesso!');
        },
        onError: (errors: any) => {
            console.log('Erros de validação:', errors.detalhes);
            console.log(errors);
            addErrorShow(errors)
            if(errors.error){
                swalFireAlert('error', 'Erro', errors.error);
            }else{
                swalFireAlert('error', 'Erro de Validação', 'Alguns campos não foram preenchidos corretamente.');
            }
        }
    });
        
    loading.stop();

    return response;
}

function addErrorShow(errors: any)
{
    const errorStore = useErrorStore();
    errorStore.clearErrors();

    if (errors) {
        errorStore.setErrors(errors);
    } else {
        errorStore.clearErrors();
    }
}