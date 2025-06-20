import axios, { AxiosRequestConfig, AxiosResponse } from "axios";
import { useLoadingStore } from "@/stores/loading";
import { swalFireAlert } from "@/config/usaForm";

async function request<T = any>(config: AxiosRequestConfig): Promise<T | null> {
    const loading = useLoadingStore();
    loading.start();

    try {
        const response: AxiosResponse<T> = await axios(config);

        if (response.data && (response.data as any).exception) {
            console.error(`Erro: ${(response.data as any).message}`);
            swalFireAlert('error', 'Erro!', (response.data as any).message);
            return null;
        }

        return response.data;
    } catch (error: any) {
        if (error.response) {
            const status = error.response.status;
            const message = error.response.data.message || 'Erro inesperado';

            if (status === 405) {
                swalFireAlert('error', 'Método Não Permitido!', message);
            } else {
                swalFireAlert('error', `Erro ${status}`, message);
            }
        } else {
            console.error(error);
            swalFireAlert('error', 'Erro!', 'Erro de comunicação com o servidor.');
        }

        return null;
    } finally {
        loading.stop();
    }
}

export async function axiosGet<T = any>(url: string, params?: Record<string, any>): Promise<T | null> {
    return request<T>({
        method: "GET",
        url,
        params,
    });
}

export async function axiosPost<T = any>(url: string, data?: Record<string, any>): Promise<T | null> {
    return request<T>({
        method: "POST",
        url,
        data,
    });
}
