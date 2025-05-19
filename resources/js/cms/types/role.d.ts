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

export type RoleType = {
    id: number;
    name: string;
    guard: string; 
    created_at: string;
    updated_at: string;
};


