export interface ParamCreateRoleType {
    roleName: string,
    selectedPermission: Array
}

export interface GroupedPermissionsType {
    [key: string]: { id: number; name: string }[]
}

export interface ParamRoleSearchType {
    page: number,
    name: string,
    paginate: boolean,
    per_page: number
}

export type PaginationLinkType = {
    url: string | null;
    label: string;
    active: boolean;
};

export type RoleType = {
    id: number;
    name: string;
    guard: string; // Note: Removed quotes around "guard" as it's not needed in TS
    created_at: string;
    updated_at: string;
};

export type AvailableRolesType = {
    current_page: number;
    data: RoleType[];
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
};

