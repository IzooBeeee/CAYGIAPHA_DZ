export type Gender = 'male' | 'female' | 'other';
export type MarriageStatus = 'married' | 'divorced' | 'widowed';

export interface Role {
  id: number;
  name: string;
  description: string | null;
}

export interface User {
  id: number;
  name: string;
  email: string;
  role: string | null; // role name
  phone?: string | null;
  avatar?: string | null;
  is_active?: boolean;
}

export interface Family {
  id: number;
  user_id: number;
  name: string;
  description: string | null;
  is_public: boolean;
  created_at: string;
  updated_at: string;
}

export interface Person {
  id: number;
  family_id: number;
  full_name: string;
  gender: Gender | null;
  birth_date: string | null;
  death_date: string | null;
  birth_place: string | null;
  avatar: string | null;
  biography: string | null;
  father_id: number | null;
  mother_id: number | null;
  created_at: string;
  updated_at: string;
  // Computed/joined
  is_deceased?: boolean;
  is_in_law?: boolean;
  birth_order?: number | null;
  generation?: number | null;
}

export interface Marriage {
  id: number;
  family_id: number;
  husband_id: number | null;
  wife_id: number | null;
  married_date: string | null;
  divorced_date: string | null;
  status: MarriageStatus;
  created_at: string;
  updated_at: string;
}

export interface TreeNode extends Person {
  spouses: Pick<Person, 'id' | 'full_name' | 'gender' | 'avatar'>[];
  children: TreeNode[];
}
