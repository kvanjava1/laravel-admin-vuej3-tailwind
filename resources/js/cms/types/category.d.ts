export type ParamsCategoryType = {
    name: string,
    isActive: number,
    parentId: number
}

export interface ParamsSearchCategoryType extends ParamsCategoryType {
    paginate: boolean,
    perPage: number,
    page: number
}

export interface CategoryType {
    id: number;
    name: string;
    parent_id: number | null;
    is_active: boolean;
    created_at: string;
    updated_at: string;
    deleted_at: string | null;
}

export interface PaginationLinkType {
    url: string | null;
    label: string;
    active: boolean;
}

export interface AvailableCategoryType{
    current_page: number;
    data: CategoryType[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: PaginationLinkType[];
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;
}
