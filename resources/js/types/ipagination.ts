export interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

export interface IPagination {
    total: number;
    data: any[];
    links: PaginationLink[];
}