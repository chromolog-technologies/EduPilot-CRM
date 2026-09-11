"use client";

import { useState } from "react";
import { MOCK_COUNSELORS, Student } from "@/lib/api";

type AddStudentModalProps = {
  isOpen: boolean;
  onClose: () => void;
  onAddStudent: (newStudent: Student) => void;
};

export default function AddStudentModal({
  isOpen,
  onClose,
  onAddStudent,
}: AddStudentModalProps) {
  const [firstName, setFirstName] = useState("");
  const [lastName, setLastName] = useState("");
  const [email, setEmail] = useState("");
  const [phone, setPhone] = useState("");
  const [city, setCity] = useState("Kochi");
  const [country, setCountry] = useState("India");
  const [desiredProgram, setDesiredProgram] = useState("MBBS - Kyrgyzstan");
  const [consultingFee, setConsultingFee] = useState<number>(185000);
  const [stage, setStage] = useState("New Inquiry");
  const [temperature, setTemperature] = useState<"Hot" | "Warm" | "Cold">("Warm");
  const [priority, setPriority] = useState<"low" | "medium" | "high">("medium");
  const [counselorId, setCounselorId] = useState<number>(2);
  const [parentName, setParentName] = useState("");
  const [parentPhone, setParentPhone] = useState("");
  const [academicSummary, setAcademicSummary] = useState("");
  const [notes, setNotes] = useState("");

  if (!isOpen) return null;

  function handleSubmit(e: React.FormEvent) {
    e.preventDefault();

    const selectedCounselor = MOCK_COUNSELORS.find((c) => c.id === counselorId);

    const newStudent: Student = {
      id: Date.now(),
      student_code: `EDU-${Math.floor(1000 + Math.random() * 9000)}`,
      first_name: firstName,
      last_name: lastName,
      email,
      phone,
      location: `${city}, ${country}`,
      city,
      country,
      desired_program: desiredProgram,
      consulting_fee: Number(consultingFee),
      stage,
      temperature,
      priority,
      counselor_name: selectedCounselor ? selectedCounselor.name : "Counselor 2 (Admin)",
      counselor_id: counselorId,
      docs_ready_count: 0,
      total_docs_count: 5,
      document_checklist: [
        { id: 1, name: "10th Marksheet", status: "pending" },
        { id: 2, name: "12th Marksheet", status: "pending" },
        { id: 3, name: "Passport", status: "pending" },
        { id: 4, name: "NEET Scorecard", status: "pending" },
        { id: 5, name: "Medical Certificate", status: "pending" },
      ],
      academic_summary: academicSummary || "12th Science graduate with Biology major.",
      background_note: notes || "New lead registered in EduPilot CRM.",
      ready_whatsapp_message: `Hi ${firstName}! Thank you for reaching out to EduPilot regarding ${desiredProgram}.`,
      notes,
      parent_name: parentName,
      parent_phone: parentPhone,
    };

    onAddStudent(newStudent);
    onClose();
  }

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/75 p-4 backdrop-blur-md">
      <div className="max-h-[92vh] w-full max-w-2xl overflow-y-auto rounded-3xl border border-slate-800 bg-slate-900/95 p-6 text-white shadow-2xl backdrop-blur-2xl">
        {/* Header */}
        <div className="flex items-center justify-between border-b border-slate-800 pb-4">
          <div>
            <span className="text-[10px] font-extrabold uppercase tracking-widest text-indigo-400">
              EduPilot • Lead Registration
            </span>
            <h2 className="text-xl font-bold text-white">Add Student Profile</h2>
          </div>
          <button
            onClick={onClose}
            className="flex h-8 w-8 items-center justify-center rounded-full bg-slate-800 text-slate-400 hover:bg-slate-700 hover:text-white"
          >
            ✕
          </button>
        </div>

        <form onSubmit={handleSubmit} className="mt-5 space-y-5 text-xs">
          {/* Section 1: Basic Information */}
          <div className="rounded-2xl border border-slate-800/80 bg-slate-950/60 p-4">
            <h3 className="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">
              1. Personal & Contact Information
            </h3>
            <div className="grid grid-cols-1 gap-3 sm:grid-cols-2">
              <div>
                <label className="block text-[11px] font-semibold text-slate-300">First Name *</label>
                <input
                  required
                  type="text"
                  className="mt-1 w-full rounded-xl border border-slate-800 bg-slate-900 px-3 py-2 text-xs text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-hidden"
                  placeholder="e.g. Akhil"
                  value={firstName}
                  onChange={(e) => setFirstName(e.target.value)}
                />
              </div>
              <div>
                <label className="block text-[11px] font-semibold text-slate-300">Last Name</label>
                <input
                  type="text"
                  className="mt-1 w-full rounded-xl border border-slate-800 bg-slate-900 px-3 py-2 text-xs text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-hidden"
                  placeholder="e.g. Suresh"
                  value={lastName}
                  onChange={(e) => setLastName(e.target.value)}
                />
              </div>
              <div>
                <label className="block text-[11px] font-semibold text-slate-300">Phone (WhatsApp) *</label>
                <input
                  required
                  type="text"
                  className="mt-1 w-full rounded-xl border border-slate-800 bg-slate-900 px-3 py-2 text-xs text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-hidden"
                  placeholder="e.g. +91 98470 12345"
                  value={phone}
                  onChange={(e) => setPhone(e.target.value)}
                />
              </div>
              <div>
                <label className="block text-[11px] font-semibold text-slate-300">Email Address</label>
                <input
                  type="email"
                  className="mt-1 w-full rounded-xl border border-slate-800 bg-slate-900 px-3 py-2 text-xs text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-hidden"
                  placeholder="e.g. student@example.com"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                />
              </div>
              <div>
                <label className="block text-[11px] font-semibold text-slate-300">City / State</label>
                <input
                  type="text"
                  className="mt-1 w-full rounded-xl border border-slate-800 bg-slate-900 px-3 py-2 text-xs text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-hidden"
                  placeholder="e.g. Kochi, Kerala"
                  value={city}
                  onChange={(e) => setCity(e.target.value)}
                />
              </div>
              <div>
                <label className="block text-[11px] font-semibold text-slate-300">Country / Region</label>
                <input
                  type="text"
                  className="mt-1 w-full rounded-xl border border-slate-800 bg-slate-900 px-3 py-2 text-xs text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-hidden"
                  placeholder="e.g. India / Qatar / UAE"
                  value={country}
                  onChange={(e) => setCountry(e.target.value)}
                />
              </div>
            </div>
          </div>

          {/* Section 2: Program & Admissions Details */}
          <div className="rounded-2xl border border-slate-800/80 bg-slate-950/60 p-4">
            <h3 className="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">
              2. Program & Admissions Details
            </h3>
            <div className="grid grid-cols-1 gap-3 sm:grid-cols-2">
              <div>
                <label className="block text-[11px] font-semibold text-slate-300">Desired Program *</label>
                <select
                  className="mt-1 w-full rounded-xl border border-slate-800 bg-slate-900 px-3 py-2 text-xs text-white focus:border-indigo-500 focus:outline-hidden"
                  value={desiredProgram}
                  onChange={(e) => setDesiredProgram(e.target.value)}
                >
                  <option value="MBBS - Kyrgyzstan">MBBS - Kyrgyzstan</option>
                  <option value="MBBS - Georgia">MBBS - Georgia</option>
                  <option value="MBBS - Uzbekistan">MBBS - Uzbekistan</option>
                  <option value="Germany Opportunity Card">Germany Opportunity Card</option>
                  <option value="Engineering - Georgia">Engineering - Georgia</option>
                  <option value="MBA - Europe">MBA - Europe</option>
                </select>
              </div>
              <div>
                <label className="block text-[11px] font-semibold text-slate-300">Consulting Fee (₹)</label>
                <input
                  type="number"
                  className="mt-1 w-full rounded-xl border border-slate-800 bg-slate-900 px-3 py-2 text-xs text-white focus:border-indigo-500 focus:outline-hidden"
                  value={consultingFee}
                  onChange={(e) => setConsultingFee(Number(e.target.value))}
                />
              </div>
              <div>
                <label className="block text-[11px] font-semibold text-slate-300">Admissions Stage</label>
                <select
                  className="mt-1 w-full rounded-xl border border-slate-800 bg-slate-900 px-3 py-2 text-xs text-white focus:border-indigo-500 focus:outline-hidden"
                  value={stage}
                  onChange={(e) => setStage(e.target.value)}
                >
                  <option value="New Inquiry">New Inquiry</option>
                  <option value="First Outreach">First Outreach</option>
                  <option value="Follow-up">Follow-up</option>
                  <option value="Counseling Meeting">Counseling Meeting</option>
                  <option value="Docs Collecting">Docs Collecting</option>
                  <option value="Applied">Applied</option>
                  <option value="Payment & Enrolled">Payment & Enrolled</option>
                </select>
              </div>
              <div>
                <label className="block text-[11px] font-semibold text-slate-300">Assigned Counselor</label>
                <select
                  className="mt-1 w-full rounded-xl border border-slate-800 bg-slate-900 px-3 py-2 text-xs text-white focus:border-indigo-500 focus:outline-hidden"
                  value={counselorId}
                  onChange={(e) => setCounselorId(Number(e.target.value))}
                >
                  {MOCK_COUNSELORS.map((c) => (
                    <option key={c.id} value={c.id}>
                      {c.name} ({c.role})
                    </option>
                  ))}
                </select>
              </div>
              <div>
                <label className="block text-[11px] font-semibold text-slate-300">Lead Temperature</label>
                <div className="mt-1 flex gap-2">
                  {(["Hot", "Warm", "Cold"] as const).map((temp) => (
                    <button
                      key={temp}
                      type="button"
                      onClick={() => setTemperature(temp)}
                      className={`flex-1 rounded-xl py-1.5 text-xs font-bold border transition-all ${
                        temperature === temp
                          ? temp === "Hot"
                            ? "bg-rose-500/20 border-rose-500 text-rose-300 glow-rose"
                            : temp === "Warm"
                            ? "bg-amber-500/20 border-amber-500 text-amber-300"
                            : "bg-slate-800 border-slate-700 text-slate-300"
                          : "border-slate-800 bg-slate-900 text-slate-500 hover:bg-slate-800"
                      }`}
                    >
                      {temp === "Hot" ? "🔥 HOT" : temp === "Warm" ? "⚡ WARM" : "❄️ COLD"}
                    </button>
                  ))}
                </div>
              </div>
              <div>
                <label className="block text-[11px] font-semibold text-slate-300">Priority Level</label>
                <select
                  className="mt-1 w-full rounded-xl border border-slate-800 bg-slate-900 px-3 py-2 text-xs text-white focus:border-indigo-500 focus:outline-hidden"
                  value={priority}
                  onChange={(e) => setPriority(e.target.value as "low" | "medium" | "high")}
                >
                  <option value="high">High Priority</option>
                  <option value="medium">Medium Priority</option>
                  <option value="low">Low Priority</option>
                </select>
              </div>
            </div>
          </div>

          {/* Section 3: Parent & Academic Summary */}
          <div className="rounded-2xl border border-slate-800/80 bg-slate-950/60 p-4">
            <h3 className="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">
              3. Parent Contact & Academic Background
            </h3>
            <div className="grid grid-cols-1 gap-3 sm:grid-cols-2">
              <div>
                <label className="block text-[11px] font-semibold text-slate-300">Parent / Guardian Name</label>
                <input
                  type="text"
                  className="mt-1 w-full rounded-xl border border-slate-800 bg-slate-900 px-3 py-2 text-xs text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-hidden"
                  placeholder="e.g. Suresh Kumar"
                  value={parentName}
                  onChange={(e) => setParentName(e.target.value)}
                />
              </div>
              <div>
                <label className="block text-[11px] font-semibold text-slate-300">Parent Phone</label>
                <input
                  type="text"
                  className="mt-1 w-full rounded-xl border border-slate-800 bg-slate-900 px-3 py-2 text-xs text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-hidden"
                  placeholder="e.g. +91 98470 99887"
                  value={parentPhone}
                  onChange={(e) => setParentPhone(e.target.value)}
                />
              </div>
              <div className="sm:col-span-2">
                <label className="block text-[11px] font-semibold text-slate-300">Academic Summary</label>
                <input
                  type="text"
                  className="mt-1 w-full rounded-xl border border-slate-800 bg-slate-900 px-3 py-2 text-xs text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-hidden"
                  placeholder="e.g. 12th CBSE Biology 75%, NEET score 420."
                  value={academicSummary}
                  onChange={(e) => setAcademicSummary(e.target.value)}
                />
              </div>
              <div className="sm:col-span-2">
                <label className="block text-[11px] font-semibold text-slate-300">Counselor Initial Notes</label>
                <textarea
                  rows={2}
                  className="mt-1 w-full rounded-xl border border-slate-800 bg-slate-900 px-3 py-2 text-xs text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-hidden"
                  placeholder="e.g. Father is working in GCC, interested in Kyrgyzstan MBBS for September intake."
                  value={notes}
                  onChange={(e) => setNotes(e.target.value)}
                />
              </div>
            </div>
          </div>

          <div className="flex justify-end gap-3 border-t border-slate-800 pt-4">
            <button
              type="button"
              onClick={onClose}
              className="rounded-xl border border-slate-800 px-4 py-2 text-xs font-semibold text-slate-400 hover:bg-slate-800 hover:text-white"
            >
              Cancel
            </button>
            <button
              type="submit"
              className="rounded-xl bg-gradient-to-r from-indigo-500 via-purple-600 to-pink-500 px-5 py-2 text-xs font-bold text-white shadow-lg hover:shadow-indigo-500/25"
            >
              Save Student Profile
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
