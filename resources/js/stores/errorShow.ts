import { defineStore } from "pinia";

interface Errors {
  [key: string]: string[]; // campo -> array de mensagens de erro
}

export const useErrorStore = defineStore<
  "errorStore",
  { errors: Errors },
  {},
  {
    setErrors(errors: Errors): void;
    clearError(field: string): void;
    clearErrors(): void;
  }
>("errorStore", {
  state: () => ({
    errors: {} as Errors,
  }),
  actions: {
    setErrors(errors: Errors) {
      this.errors = errors;
    },
    clearError(field: string) {
      if (this.errors[field]) {
        delete this.errors[field];
      }
    },
    clearErrors() {
      this.errors = {};
    },
  },
  persist: true,
});
