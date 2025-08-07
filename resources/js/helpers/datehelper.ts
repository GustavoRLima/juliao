import { format } from "date-fns";
import { ptBR } from "date-fns/locale";

const formatarDataParaInput = (date: Date): string => {
    return date.toISOString().split('T')[0];
};

const helperData = {

    dataAtualFormat: formatarDataParaInput(new Date()),
    
    formatarDataParaInput,
    
    formatarDataHora: (data: string) => {
      return format(new Date(data), "dd/MM/yyyy HH:mm", { locale: ptBR });
    },
    
    formatarData: (data: string) => {
      return format(new Date(data), "dd/MM/yyyy", { locale: ptBR });
    },
}


export default helperData;