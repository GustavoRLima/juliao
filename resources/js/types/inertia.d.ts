import { Page } from '@inertiajs/core'

declare module '@inertiajs/vue3' {
  export function usePage<TPageProps = PageProps>(): Page<TPageProps>
}

export interface PageProps {
  auth: {
    user: {
      id: number
      name: string
      email: string
      // outros campos do usuário
    }
  }
  ziggy: {
    location: string
    routes: Record<string, any>
  }
  flash?: {
    message?: {
      success?: string
      error?: string
    }
  }
}