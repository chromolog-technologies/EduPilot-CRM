"use client";

import { useState } from "react";
import AppShell from "@/components/AppShell";

export default function AutomationsPage() {
  const [activeTab, setActiveTab] = useState<"assignment" | "duplicates" | "sequences">("assignment");

  const rules = [
    { id: 1, name: "Round Robin GCC NRI Leads", priority: 1, type: "Least Loaded", active: true, assigned_counselors: "Counselor 4, Counselor 2 (Admin)" },
    { id: 2, name: "Kerala Medical Inquiries", priority: 2, type: "Round Robin", active: true, assigned_counselors: "Counselor 1, Counselor 3" },
  ];

  const duplicates = [
    { id: 1, lead_name: "Arjun Krishnan", phone: "+91 94470 23456", duplicate_name: "Arjun K. (Old Inquiry)", match_type: "phone", match_score: "95%", status: "Pending Review" },
    { id: 2, lead_name: "Mohammed Shafeeq", phone: "+971 50 123 4567", duplicate_name: "Shafeeq M. (Web Lead)", match_type: "email", match_score: "88%", status: "Confirmed Duplicate" },
  ];

  const sequences = [
    { id: 1, name: "Standard 7-Day Medical Intake Nurturing", trigger: "Lead Created", steps: 3, status: "Active", stop_on_reply: true },
    { id: 2, name: "Missing Documents Reminder Automation", trigger: "Stage: Docs Collecting", steps: 2, status: "Active", stop_on_reply: true },
  ];

  return (
    <AppShell>
      <div className="space-y-6">
        {/* Header */}
        <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
          <div>
            <div className="flex items-center gap-2">
              <span className="rounded-full bg-indigo-100 px-2.5 py-0.5 text-[9px] font-extrabold text-indigo-700 border border-indigo-200 uppercase tracking-wider dark:bg-indigo-500/20 dark:text-indigo-300 dark:border-indigo-500/30">
                Gold Automation Suite
              </span>
            </div>
            <h1 className="mt-1 text-2xl font-black tracking-tight text-slate-900 dark:text-white">
              Automation Rules & Followup Sequences
            </h1>
            <p className="mt-0.5 text-xs font-medium text-slate-500 dark:text-slate-400">
              Configure automatic counselor assignment, duplicate lead resolution, and multi-step follow-up sequences.
            </p>
          </div>
        </div>

        {/* Navigation Tabs */}
        <div className="flex rounded-2xl border border-slate-200 bg-slate-100 p-1 text-xs font-semibold dark:border-slate-800 dark:bg-slate-900">
          <button
            onClick={() => setActiveTab("assignment")}
            className={`flex-1 rounded-xl py-2 text-center transition-all ${
              activeTab === "assignment" ? "bg-gradient-to-r from-indigo-600 to-purple-600 font-bold text-white shadow-md" : "text-slate-600 dark:text-slate-400"
            }`}
          >
            Auto Assignment Rules
          </button>
          <button
            onClick={() => setActiveTab("duplicates")}
            className={`flex-1 rounded-xl py-2 text-center transition-all ${
              activeTab === "duplicates" ? "bg-gradient-to-r from-indigo-600 to-purple-600 font-bold text-white shadow-md" : "text-slate-600 dark:text-slate-400"
            }`}
          >
            Duplicate Lead Detection ({duplicates.length})
          </button>
          <button
            onClick={() => setActiveTab("sequences")}
            className={`flex-1 rounded-xl py-2 text-center transition-all ${
              activeTab === "sequences" ? "bg-gradient-to-r from-indigo-600 to-purple-600 font-bold text-white shadow-md" : "text-slate-600 dark:text-slate-400"
            }`}
          >
            Follow-up Sequences ({sequences.length})
          </button>
        </div>

        {/* TAB 1: AUTO ASSIGNMENT */}
        {activeTab === "assignment" ? (
          <div className="space-y-4">
            <div className="flex items-center justify-between">
              <h2 className="text-sm font-bold text-slate-900 dark:text-white">Active Assignment Rules</h2>
              <button onClick={() => alert("Creating new rule...")} className="rounded-xl bg-indigo-600 px-3.5 py-1.5 text-xs font-bold text-white shadow-md">
                + New Rule
              </button>
            </div>

            <div className="space-y-3">
              {rules.map((rule) => (
                <div key={rule.id} className="flex items-center justify-between rounded-3xl border border-slate-200/90 bg-white/90 p-5 shadow-xl shadow-slate-200/50 backdrop-blur-xl dark:border-slate-800/90 dark:bg-slate-900/80 dark:shadow-none">
                  <div>
                    <div className="flex items-center gap-2">
                      <span className="rounded-md bg-indigo-100 px-2 py-0.5 text-[9px] font-bold text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300">
                        Priority #{rule.priority}
                      </span>
                      <h3 className="text-sm font-bold text-slate-900 dark:text-white">{rule.name}</h3>
                    </div>
                    <p className="mt-1 text-xs text-slate-500 dark:text-slate-400">
                      Algorithm: <span className="font-bold text-indigo-600 dark:text-indigo-400">{rule.type}</span> • Counselors: {rule.assigned_counselors}
                    </p>
                  </div>

                  <span className="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30">
                    Active
                  </span>
                </div>
              ))}
            </div>
          </div>
        ) : null}

        {/* TAB 2: DUPLICATE DETECTION */}
        {activeTab === "duplicates" ? (
          <div className="space-y-4">
            <h2 className="text-sm font-bold text-slate-900 dark:text-white">Potential Lead Match Flags</h2>
            <div className="space-y-3">
              {duplicates.map((dup) => (
                <div key={dup.id} className="flex flex-col gap-3 rounded-3xl border border-slate-200/90 bg-white/90 p-5 shadow-xl shadow-slate-200/50 backdrop-blur-xl dark:border-slate-800/90 dark:bg-slate-900/80 dark:shadow-none sm:flex-row sm:items-center sm:justify-between">
                  <div>
                    <div className="flex items-center gap-2">
                      <span className="rounded-md bg-rose-100 px-2 py-0.5 text-[9px] font-bold text-rose-700 dark:bg-rose-500/20 dark:text-rose-300">
                        Match Score: {dup.match_score} ({dup.match_type})
                      </span>
                      <h3 className="text-sm font-bold text-slate-900 dark:text-white">{dup.lead_name}</h3>
                    </div>
                    <p className="mt-1 text-xs text-slate-500 dark:text-slate-400">
                      Matched against: <span className="font-bold text-slate-900 dark:text-white">{dup.duplicate_name}</span> ({dup.phone})
                    </p>
                  </div>

                  <div className="flex items-center gap-2">
                    <button onClick={() => alert("Merged lead!")} className="rounded-xl bg-emerald-600 px-3.5 py-1.5 text-xs font-bold text-white shadow-md">
                      Merge Leads
                    </button>
                    <button onClick={() => alert("Dismissed match")} className="rounded-xl border border-slate-200 bg-slate-100 px-3.5 py-1.5 text-xs font-bold text-slate-700 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-300">
                      Not Duplicate
                    </button>
                  </div>
                </div>
              ))}
            </div>
          </div>
        ) : null}

        {/* TAB 3: FOLLOWUP SEQUENCES */}
        {activeTab === "sequences" ? (
          <div className="space-y-4">
            <div className="flex items-center justify-between">
              <h2 className="text-sm font-bold text-slate-900 dark:text-white">Multi-Step Automation Sequences</h2>
              <button onClick={() => alert("Creating sequence...")} className="rounded-xl bg-indigo-600 px-3.5 py-1.5 text-xs font-bold text-white shadow-md">
                + Create Sequence
              </button>
            </div>

            <div className="space-y-3">
              {sequences.map((seq) => (
                <div key={seq.id} className="rounded-3xl border border-slate-200/90 bg-white/90 p-5 shadow-xl shadow-slate-200/50 backdrop-blur-xl dark:border-slate-800/90 dark:bg-slate-900/80 dark:shadow-none">
                  <div className="flex items-center justify-between border-b border-slate-100 pb-3 dark:border-slate-800">
                    <div>
                      <h3 className="text-sm font-bold text-slate-900 dark:text-white">{seq.name}</h3>
                      <p className="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Trigger: <span className="font-bold text-indigo-600 dark:text-indigo-400">{seq.trigger}</span> • Steps: {seq.steps} automated WhatsApp actions
                      </p>
                    </div>
                    <span className="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30">
                      {seq.status}
                    </span>
                  </div>

                  <div className="mt-3 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                    <span>⚡ Stop on Reply: Enabled</span>
                    <button className="font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                      Edit Sequence Steps →
                    </button>
                  </div>
                </div>
              ))}
            </div>
          </div>
        ) : null}
      </div>
    </AppShell>
  );
}
