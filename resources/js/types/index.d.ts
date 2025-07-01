export type User = {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string;
    role: 'admin';
    admin: {
        name: string;
    }
} | {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string;
    role: 'patient';
    patient: {
        first_name: string;
        last_name: string;
    }
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user?: User;
    };
};
