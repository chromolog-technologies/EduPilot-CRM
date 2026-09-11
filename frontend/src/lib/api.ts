const API_URL = process.env.NEXT_PUBLIC_API_URL ?? "http://localhost:8000/api/v1";

export type ApiSuccess<T> = {
  success: true;
  message: string;
  data: T;
  meta?: {
    current_page: number;
    per_page: number;
    total: number;
    last_page: number;
  };
};

export type Counselor = {
  id: number;
  name: string;
  role: string;
  assigned_students_count: number;
  avg_response_mins: number;
  enrollment_rate: number;
  status: "Active" | "Inactive";
};

export type StudentDocumentItem = {
  id: number;
  name: string;
  status: "verified" | "missing" | "pending";
};

export type Student = {
  id: number;
  student_code: string;
  first_name: string;
  last_name: string;
  email: string;
  phone: string;
  location: string;
  city?: string;
  country?: string;
  desired_program: string;
  consulting_fee: number;
  stage: string;
  temperature: "Hot" | "Warm" | "Cold";
  priority: "low" | "medium" | "high";
  counselor_name: string;
  counselor_id?: number;
  docs_ready_count: number;
  total_docs_count: number;
  document_checklist: StudentDocumentItem[];
  academic_summary?: string;
  background_note?: string;
  ready_whatsapp_message?: string;
  notes?: string;
  parent_name?: string;
  parent_phone?: string;
};

export type PipelineStageItem = {
  id: number;
  name: string;
  slug: string;
  count: number;
  total_fee: number;
  students: Student[];
};

export type FollowupCard = {
  id: number;
  student_id: number;
  student_name: string;
  program: string;
  location: string;
  fee: number;
  column: "to_reach_today" | "message_sent" | "replied" | "call_scheduled";
  background: string[];
  ready_message: string;
};

export type ChatMessage = {
  id: number;
  sender: "student" | "counselor" | "system";
  text: string;
  timestamp: string;
  status?: "sent" | "delivered" | "read";
};

export type ChatConversation = {
  id: number;
  student_id: number;
  student_name: string;
  location: string;
  desired_program: string;
  counselor_name: string;
  temperature: "Hot" | "Warm" | "Cold";
  stage: string;
  last_message: string;
  last_time: string;
  needs_reply: boolean;
  messages: ChatMessage[];
};

export type ProgramRevenue = {
  program: string;
  revenue: number;
  progress_percent: number;
};

export type DashboardSummary = {
  enrolled_revenue_this_month: number;
  enrolled_target: number;
  active_pipeline_fees: number;
  prospective_students_count: number;
  enrollment_rate: number;
  avg_response_time_mins: number;
  counselors: Counselor[];
  program_revenues: ProgramRevenue[];
};

// ==========================================
// GOLD VERSION TYPES & DATA CONTRACTS
// ==========================================
export type GoldCampaign = {
  id: number;
  name: string;
  code: string;
  platform: "facebook" | "instagram" | "google" | "website" | "whatsapp";
  budget: number;
  leads_generated: number;
  conversions: number;
  status: "active" | "paused" | "completed";
};

export type GoldLeadCapture = {
  id: number;
  external_lead_id: string;
  source_name: string;
  student_name: string;
  phone: string;
  capture_status: "processed" | "processing" | "duplicate" | "failed";
  timestamp: string;
};

export type GoldAssignmentRule = {
  id: number;
  name: string;
  priority: number;
  assignment_type: "least_loaded" | "round_robin" | "specific";
  is_active: boolean;
  assigned_counselors_count: number;
};

export type GoldFollowupStep = {
  id: number;
  step_order: number;
  delay_value: number;
  delay_unit: "hours" | "days";
  channel: "whatsapp" | "email";
  message_content: string;
};

export type GoldFollowupSequence = {
  id: number;
  name: string;
  trigger_type: "lead_created" | "stage_changed" | "no_response";
  status: "active" | "paused";
  stop_on_reply: boolean;
  steps: GoldFollowupStep[];
};

export type GoldIntegration = {
  id: number;
  name: string;
  type: "whatsapp" | "facebook" | "instagram" | "website" | "webhook";
  status: "connected" | "disconnected" | "syncing";
  last_synced_at: string;
};

export type GoldAiMessage = {
  id: number;
  student_name: string;
  purpose: "first_outreach" | "followup" | "document_reminder" | "fee_reminder";
  language: string;
  tone: string;
  generated_message: string;
  status: "generated" | "approved" | "rejected";
};

export async function api<T>(
  path: string,
  options: RequestInit = {},
  token?: string | null
): Promise<ApiSuccess<T>> {
  const headers = new Headers(options.headers);
  headers.set("Accept", "application/json");

  if (!(options.body instanceof FormData)) {
    headers.set("Content-Type", "application/json");
  }

  if (token) {
    headers.set("Authorization", `Bearer ${token}`);
  }

  const response = await fetch(`${API_URL}${path}`, {
    ...options,
    headers,
  });

  const json = await response.json();

  if (!response.ok) {
    throw new Error(json.message ?? "Request failed");
  }

  return json;
}

// Initial EduPilot Reference Dataset
export const MOCK_COUNSELORS: Counselor[] = [
  { id: 1, name: "Counselor 1", role: "Senior Admissions Advisor (Kerala)", assigned_students_count: 11, avg_response_mins: 14, enrollment_rate: 42, status: "Active" },
  { id: 2, name: "Counselor 2 (Admin)", role: "Admissions Director & Admin", assigned_students_count: 18, avg_response_mins: 9, enrollment_rate: 55, status: "Active" },
  { id: 3, name: "Counselor 3", role: "Global Admissions Specialist (Europe/CIS)", assigned_students_count: 9, avg_response_mins: 18, enrollment_rate: 38.5, status: "Active" },
  { id: 4, name: "Counselor 4", role: "GCC & NRI Student Advisor", assigned_students_count: 13, avg_response_mins: 11, enrollment_rate: 47.2, status: "Active" },
];

export const MOCK_STUDENTS: Student[] = [
  {
    id: 101,
    student_code: "EDU-1001",
    first_name: "Arjun",
    last_name: "Krishnan",
    email: "arjun.k@example.com",
    phone: "+91 94470 23456",
    location: "Thrissur, Kerala, India",
    city: "Thrissur",
    country: "India",
    desired_program: "MBBS - Kyrgyzstan",
    consulting_fee: 185000,
    stage: "Counseling Meeting",
    temperature: "Hot",
    priority: "high",
    counselor_name: "Counselor 2 (Admin)",
    counselor_id: 2,
    docs_ready_count: 2,
    total_docs_count: 5,
    document_checklist: [
      { id: 1, name: "10th Marksheet (Original)", status: "verified" },
      { id: 2, name: "12th Marksheet (Original)", status: "verified" },
      { id: 3, name: "Passport (Valid)", status: "verified" },
      { id: 4, name: "Passport-size Photos (6 copies)", status: "missing" },
      { id: 5, name: "Medical Fitness Certificate", status: "pending" },
    ],
    academic_summary: "CBSE 12th Biology: 72% - well above 50% MCI requirement.",
    background_note: "Parents attended previous EduPilot webinar. High readiness to pay seat booking fee.",
    ready_whatsapp_message: "Hi Arjun! This is your advisor from EduPilot CRM. Just checking in, have you collected the 12th original marksheet?",
    notes: "Very Interested in Kyrgyzstan MBBS. 12th marksheet in hand, biology 72%. Document review call scheduled.",
    parent_name: "Krishnan Nair",
    parent_phone: "+91 98470 11223"
  },
  {
    id: 102,
    student_code: "EDU-1002",
    first_name: "Fathima",
    last_name: "Noushad",
    email: "fathima.n@example.com",
    phone: "+965 9912 3456",
    location: "Kuwait City / Kochi",
    city: "Kuwait City",
    country: "Kuwait (NRI)",
    desired_program: "MBBS - Georgia",
    consulting_fee: 220000,
    stage: "Applied",
    temperature: "Hot",
    priority: "high",
    counselor_name: "Counselor 4",
    counselor_id: 4,
    docs_ready_count: 4,
    total_docs_count: 5,
    document_checklist: [
      { id: 1, name: "10th Marksheet (Original)", status: "verified" },
      { id: 2, name: "12th Marksheet (Original)", status: "verified" },
      { id: 3, name: "Passport (Valid)", status: "verified" },
      { id: 4, name: "NEET Scorecard", status: "verified" },
      { id: 5, name: "Financial Statement", status: "pending" },
    ],
    academic_summary: "NEET score: 465 - qualifies for Georgia MBBS (NRI quota, no NEET cut-off issue).",
    background_note: "Referred by a previous EduPilot student (Safiya - enrolled batch 2025). High trust factor.",
    ready_whatsapp_message: "Hi Fathima! Hope you're doing well. Quick update from EduPilot: your application dossier for Georgia is submitted!",
    notes: "Application dossier sent to university admissions office.",
    parent_name: "Noushad Ahmad",
    parent_phone: "+965 6677 8899"
  },
  {
    id: 103,
    student_code: "EDU-1003",
    first_name: "Mohammed",
    last_name: "Shafeeq",
    email: "m.shafeeq@example.com",
    phone: "+971 50 123 4567",
    location: "Dubai, UAE",
    city: "Dubai",
    country: "UAE",
    desired_program: "Germany Opportunity Card",
    consulting_fee: 92000,
    stage: "First Outreach",
    temperature: "Warm",
    priority: "medium",
    counselor_name: "Counselor 4",
    counselor_id: 4,
    docs_ready_count: 1,
    total_docs_count: 4,
    document_checklist: [
      { id: 1, name: "Degree Certificate", status: "verified" },
      { id: 2, name: "German Language A1/A2 Certificate", status: "missing" },
      { id: 3, name: "Experience Letters", status: "pending" },
      { id: 4, name: "Passport", status: "pending" },
    ],
    academic_summary: "3 years hospitality management experience in UAE.",
    background_note: "Germany Opportunity Card requires proof of qualification or relevant work experience — he qualifies.",
    ready_whatsapp_message: "Hi Mohammed! Hope work's going well in Dubai. Following up from EduPilot on Germany Opportunity Card documents.",
    notes: "Requires guidance on German language course enrolment.",
  },
  {
    id: 104,
    student_code: "EDU-1004",
    first_name: "Akhil",
    last_name: "Suresh",
    email: "akhil.s@example.com",
    phone: "+974 55 987 654",
    location: "Doha, Qatar",
    city: "Doha",
    country: "Qatar",
    desired_program: "MBBS - Uzbekistan",
    consulting_fee: 160000,
    stage: "New Inquiry",
    temperature: "Warm",
    priority: "medium",
    counselor_name: "Counselor 3",
    counselor_id: 3,
    docs_ready_count: 0,
    total_docs_count: 3,
    document_checklist: [
      { id: 1, name: "12th Marksheet", status: "pending" },
      { id: 2, name: "Passport", status: "pending" },
      { id: 3, name: "NEET Card", status: "pending" },
    ],
    academic_summary: "Fresh Class 12 graduate (CBSE) with science stream.",
    background_note: "Father is working in Qatar, so family has GCC income — financially stable.",
    ready_whatsapp_message: "Hi Akhil! Congratulations on finishing 12th. EduPilot received your DM regarding MBBS in Uzbekistan.",
    notes: "Inquired via Instagram DM.",
  }
];

export const MOCK_CONVERSATIONS: ChatConversation[] = [
  {
    id: 1,
    student_id: 101,
    student_name: "Arjun Krishnan",
    location: "Thrissur, Kerala, India",
    desired_program: "MBBS - Kyrgyzstan",
    counselor_name: "Counselor 2 (Admin)",
    temperature: "Hot",
    stage: "Counseling Meeting",
    last_message: "Hi Arjun! Welcome to EduPilot 😊 Kyrgyzstan MBBS is one of the best options...",
    last_time: "18:56",
    needs_reply: false,
    messages: [
      {
        id: 1,
        sender: "student",
        text: "Hi, I saw your reel about MBBS in Kyrgyzstan. Can you share more details about fees and admission?",
        timestamp: "17:58"
      },
      {
        id: 2,
        sender: "counselor",
        text: "Hi Arjun! Welcome to EduPilot 😊 Kyrgyzstan MBBS is one of the best options for Indian students. It is MCI-recognised, affordable, and direct admission with 60%+ biology. Want me to send you the full program details?",
        timestamp: "18:56",
        status: "read"
      }
    ]
  }
];

export const MOCK_CAMPAIGNS: GoldCampaign[] = [
  { id: 1, name: "Facebook GCC MBBS Intake 2026", code: "FB-MBBS-2026", platform: "facebook", budget: 50000, leads_generated: 48, conversions: 21, status: "active" },
  { id: 2, name: "Instagram Reels Medical Outreach", code: "IG-REELS-MED", platform: "instagram", budget: 35000, leads_generated: 32, conversions: 14, status: "active" },
  { id: 3, name: "Google Search Georgia MBBS", code: "GOOG-GA-MBBS", platform: "google", budget: 25000, leads_generated: 21, conversions: 9, status: "active" },
];

export const MOCK_INTEGRATIONS: GoldIntegration[] = [
  { id: 1, name: "Meta WhatsApp Business API", type: "whatsapp", status: "connected", last_synced_at: "Just now" },
  { id: 2, name: "Facebook Lead Ads Integration", type: "facebook", status: "connected", last_synced_at: "5 mins ago" },
  { id: 3, name: "Website Inquiry Webhook", type: "webhook", status: "connected", last_synced_at: "12 mins ago" },
];
