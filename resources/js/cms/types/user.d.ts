export interface ParamsUserType {
    name: string,
    email: string,
    password: string,
    passwordConfirmation: string,
    roleId: number,
    activeStatus: number
}

export interface ParamsSearchUserType extends ParamsUserType {
    paginate: boolean,
    perPage: number,
    page: number
}

export interface Pivot {
    model_type: string;
    model_id: number;
    role_id: number;
}

export interface RoleType {
    id: number;
    name: string;
    guard_name: string;
    pivot: Pivot;
}

export interface UserType {
    id: number;
    name: string;
    email: string;
    is_active: number; // Or boolean, depending on how you want to represent it
    created_at: string;
    updated_at: string;
    roles: Role[];
}
