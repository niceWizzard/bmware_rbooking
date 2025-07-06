export type User = {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string;
} & (
    | {
          role: 'admin';
          admin: Admin;
      }
    | {
          role: 'patient';
          patient: Patient;
      }
);

export interface UserPatient {
    id: number;
    first_name: string;
    last_name: string;
}

export interface UserAdmin {
    id: number;
    name: string;
}

export interface Doctor {
    id: number;
    name: string;
    code: string;
    specialty: string;
    schedules_exists: boolean;
}

export interface Schedule {
    id: number;
    clinic: string;
    start_at: string;
    end_at: string;
    day: number;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user?: User;
    };
    flash?: {
        message: string;
    };
};
