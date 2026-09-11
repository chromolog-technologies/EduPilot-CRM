"use client";

import { useState } from "react";
import AppShell from "@/components/AppShell";
import { MOCK_STUDENTS, Student } from "@/lib/api";

export default function AiMessageGeneratorPage() {
  const [selectedStudentId, setSelectedStudentId] = useState<number>(101);
  const [purpose, setPurpose] = useState<"first_outreach" | "followup" | "document_reminder" | "fee_reminder">("first_outreach");
  const [language, setLanguage] = useState("English");
  const [tone, setTone] = useState("professional");
  const [isGenerating, setIsGenerating] = useState(false);
  const [generatedDraft, setGeneratedDraft] = useState(
    "Hi Arjun! Greetings from EduPilot CRM 🎓 Regarding your inquiry for MBBS in Kyrgyzstan: our admissions panel has verified your 12th biology eligibility (72%). Would you be available for a brief 5-minute video call today at 4:00 PM?"
  );

  const selectedStudent = MOCK_STUDENTS.find((s) => s.id === selectedStudentId) ?? MOCK_STUDENTS[0];

  function handleGenerateDraft() {
    setIsGenerating(true);
    setTimeout(() => {
      if (purpose === "document_reminder") {
        setGeneratedDraft(
          `Hi ${selectedStudent.first_name}! EduPilot Admissions update: We require your 12th marksheet and passport copy to process your ${selectedStudent.desired_program} application dossier. Please send them via WhatsApp reply.`
        );
      } else if (purpose === "fee_reminder") {
        setGeneratedDraft(
          `Hi ${selectedStudent.first_name}! Friendly reminder from EduPilot: The seat booking deposit for ${selectedStudent.desired_program} intake is due this week to lock in your university slot.`
        );
      } else {
        setGeneratedDraft(
          `Hi ${selectedStudent.first_name}! Greetings from EduPilot CRM 🎓 Regarding your inquiry for ${selectedStudent.desired_program}: our admissions panel has verified your eligibility criteria. Would you be available for a brief 5-minute video call today at 4:00 PM?`
        );
      }
      setIsGenerating(false);
    }, 600);
  }

  return (
    <AppShell>
      <div className="space-y-6">
        {/* Header */}
        <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
          <div>
            <div className="flex items-center gap-2">
              <span className="rounded-full bg-indigo-100 px-2.5 py-0.5 text-[9px] font-extrabold text-indigo-700 border border-indigo-200 uppercase tracking-wider dark:bg-indigo-500/20 dark:text-indigo-300 dark:border-indigo-500/30">
                Gold AI Copywriter
              </span>
            </div>
            <h1 className="mt-1 text-2xl font-black tracking-tight text-slate-900 dark:text-white">
              AI Message Generator & Copywriting Studio
            </h1>
            <p className="mt-0.5 text-xs font-medium text-slate-500 dark:text-slate-400">
              Generate personalized WhatsApp outreach messages using student academic context and intake milestones.
            </p>
          </div>
        </div>

        {/* Studio Workspace Grid */}
        <div className="grid grid-cols-1 gap-6 lg:grid-cols-12">
          {/* Left Column: Generation Controls (5 COLS) */}
          <div className="lg:col-span-5 rounded-3xl border border-slate-200/90 bg-white/90 p-5 shadow-xl shadow-slate-200/50 backdrop-blur-xl dark:border-slate-800/90 dark:bg-slate-900/80 dark:shadow-none space-y-4">
            <h2 className="text-xs font-bold uppercase tracking-wider text-slate-400">AI CONTEXT GENERATOR</h2>

            <div className="space-y-3 text-xs">
              <div>
                <label className="block font-bold text-slate-700 dark:text-slate-300 mb-1">Select Student Dossier</label>
                <select
                  value={selectedStudentId}
                  onChange={(e) => setSelectedStudentId(Number(e.target.value))}
                  className="w-full rounded-xl border border-slate-200 bg-slate-50 p-2.5 font-bold text-slate-900 focus:border-indigo-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white"
                >
                  {MOCK_STUDENTS.map((s) => (
                    <option key={s.id} value={s.id}>
                      {s.first_name} {s.last_name} ({s.desired_program})
                    </option>
                  ))}
                </select>
              </div>

              <div>
                <label className="block font-bold text-slate-700 dark:text-slate-300 mb-1">Outreach Purpose</label>
                <select
                  value={purpose}
                  onChange={(e) => setPurpose(e.target.value as any)}
                  className="w-full rounded-xl border border-slate-200 bg-slate-50 p-2.5 font-bold text-slate-900 focus:border-indigo-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white"
                >
                  <option value="first_outreach">First Outreach & Introduction</option>
                  <option value="followup">General Admission Follow-up</option>
                  <option value="document_reminder">Document Checklist Reminder</option>
                  <option value="fee_reminder">Fee Deposit Reminder</option>
                </select>
              </div>

              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="block font-bold text-slate-700 dark:text-slate-300 mb-1">Language</label>
                  <select
                    value={language}
                    onChange={(e) => setLanguage(e.target.value)}
                    className="w-full rounded-xl border border-slate-200 bg-slate-50 p-2.5 font-bold text-slate-900 focus:border-indigo-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white"
                  >
                    <option>English</option>
                    <option>Malayalam</option>
                    <option>Hindi</option>
                  </select>
                </div>

                <div>
                  <label className="block font-bold text-slate-700 dark:text-slate-300 mb-1">Tone</label>
                  <select
                    value={tone}
                    onChange={(e) => setTone(e.target.value)}
                    className="w-full rounded-xl border border-slate-200 bg-slate-50 p-2.5 font-bold text-slate-900 focus:border-indigo-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white"
                  >
                    <option value="professional">Professional</option>
                    <option value="friendly">Friendly & Warm</option>
                    <option value="urgent">Urgent Deadline</option>
                  </select>
                </div>
              </div>

              <button
                onClick={handleGenerateDraft}
                disabled={isGenerating}
                className="w-full flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 py-3 text-xs font-bold text-white shadow-lg hover:opacity-95 transition-all disabled:opacity-50"
              >
                <span>✨</span>
                <span>{isGenerating ? "Generating Draft..." : "Generate AI Message"}</span>
              </button>
            </div>
          </div>

          {/* Right Column: Draft Preview & Approval (7 COLS) */}
          <div className="lg:col-span-7 flex flex-col justify-between rounded-3xl border border-slate-200/90 bg-white/90 p-5 shadow-xl shadow-slate-200/50 backdrop-blur-xl dark:border-slate-800/90 dark:bg-slate-900/80 dark:shadow-none space-y-4">
            <div>
              <div className="flex items-center justify-between border-b border-slate-100 pb-3 dark:border-slate-800">
                <div>
                  <span className="text-[10px] font-extrabold text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">
                    AI GENERATED DRAFT PREVIEW
                  </span>
                  <h3 className="text-sm font-bold text-slate-900 dark:text-white">
                    Target: {selectedStudent.first_name} {selectedStudent.last_name}
                  </h3>
                </div>
                <span className="rounded-full bg-emerald-100 px-2.5 py-0.5 text-[9px] font-bold text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300">
                  Ready to Review
                </span>
              </div>

              <div className="mt-4 rounded-2xl border border-slate-200 bg-slate-50 p-4 font-mono text-xs text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 leading-relaxed min-h-[140px]">
                {generatedDraft}
              </div>
            </div>

            <div className="flex items-center justify-between border-t border-slate-100 pt-3 dark:border-slate-800">
              <button
                onClick={() => alert("Copied AI draft to clipboard!")}
                className="rounded-xl border border-slate-200 bg-slate-100 px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-200 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-300"
              >
                Copy Draft
              </button>

              <div className="flex items-center gap-2">
                <button
                  onClick={handleGenerateDraft}
                  className="rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-2 text-xs font-bold text-indigo-700 hover:bg-indigo-100 dark:border-indigo-500/30 dark:bg-indigo-500/10 dark:text-indigo-300"
                >
                  Regenerate
                </button>
                <button
                  onClick={() => alert("Approved & queued for WhatsApp send!")}
                  className="rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 px-5 py-2 text-xs font-bold text-white shadow-md hover:opacity-90"
                >
                  Approve & Send
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </AppShell>
  );
}
