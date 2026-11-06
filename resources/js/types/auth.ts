export type UserMode = 'student' | 'parent' | 'teacher' | 'assistant' | 'admin';

export type TeacherRequestStatus = 'pending' | 'approved' | 'rejected';

export type User = {
    id: number;
    name: string;
    email: string;
    phone?: string | null;
    avatar?: string;
    email_verified_at: string | null;
    locale?: string | null;
    roles: UserMode[];
    active_mode: UserMode | null;
    is_staff: boolean;
    teacher_request_status: TeacherRequestStatus | null;
    two_factor_enabled?: boolean;
    created_at: string;
    updated_at: string;
    [key: string]: unknown;
};

export type Auth = {
    user: User | null;
};

export type Passkey = {
    id: number;
    name: string;
    authenticator: string | null;
    created_at_diff: string;
    last_used_at_diff: string | null;
};

export type TwoFactorSetupData = {
    svg: string;
    url: string;
};

export type TwoFactorSecretKey = {
    secretKey: string;
};
