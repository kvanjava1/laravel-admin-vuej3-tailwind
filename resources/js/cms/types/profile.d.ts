export interface ParamsProfileType {
    name: string,
    email: string,
    password: string,
    passwordConfirmation: string,
    oldPassword: string,
    activeStatus: number
}

export interface ProfileDetailType {
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
    is_active: boolean | null;
    created_at: string; // Or Date if you parse it
    updated_at: string; // Or Date if you parse it
    deleted_at: string | null; // Soft delete support
    roles?: Role[]; // Optional if you have role relationships
}