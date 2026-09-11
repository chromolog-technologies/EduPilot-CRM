"use client";

import { useState } from "react";
import AppShell from "@/components/AppShell";
import { FollowupCard } from "@/lib/api";

export default function FollowupsPage() {
  const [selectedDate, setSelectedDate] = useState<"yesterday" | "today" | "tomorrow">("today");
  const [searchQuery, setSearchQuery] = useState("");

  const [cards, setCards] = useState<FollowupCard[]>([
    {
      id: 1,
      student_id: 104,
      student_name: "Akhil Suresh",
      program: "MBBS Uzbekistan",
      location: "Qatar",
      fee: 160000,
      column: "to_reach_today",
      background: [
        "Fresh Class 12 graduate (CBSE) with science stream. Biology student — eligible for MBBS programs.",
        "Father is working in Qatar, so family has GCC income — financially stable.",
      ],
      ready_message: "Hi Akhil! Congratulations on finishing 12th 🎓 EduPilot received your DM regarding MBBS in Uzbekistan.",
    },
    {
      id: 2,
      student_id: 103,
      student_name: "Mohammed Shafeeq",
      program: "Germany Opportunity Card",
      location: "Dubai",
      fee: 92000,
      column: "message_sent",
      background: [
        "3 years hospitality management experience in UAE.",
        "Germany Opportunity Card requires proof of qualification or relevant work experience — he qualifies.",
      ],
      ready_message: "Hi Mohammed! Hope work's going well in Dubai 🌟 EduPilot following up on Germany Opportunity Card docs.",
    },
    {
      id: 3,
      student_id: 109,
      student_name: "Anandhu Nair",
      program: "Engineering Georgia",
      location: "Kerala",
      fee: 135000,
      column: "message_sent",
      background: [
        "Interested in Mechanical & Aerospace engineering.",
        "English medium instruction with European curriculum.",
      ],
      ready_message: "Hi Anandhu! Thanks for watching EduPilot's YouTube guide on Georgia engineering admissions...",
    },
    {
      id: 4,
      student_id: 102,
      student_name: "Fathima Noushad",
      program: "MBBS Georgia",
      location: "Kuwait",
      fee: 220000,
      column: "replied",
      background: [
        "Referred by a previous EduPilot student (Safiya — enrolled batch 2025). High trust factor.",
        "NEET score: 465 — qualifies for Georgia MBBS (NRI quota, no NEET cut-off issue).",
      ],
      ready_message: "Hi Fathima! Hope you're doing well 😃 Quick update from EduPilot: your application dossier is ready...",
    },
    {
      id: 5,
      student_id: 107,
      student_name: "Sreelakshmi Pillai",
      program: "MBBS Kyrgyzstan",
      location: "Oman",
      fee: 185000,
      column: "replied",
      background: [
        "Father is a practicing doctor in Oman — strong medical background and motivation.",
        "78% CBSE biology — direct eligibility for Kyrgyzstan MBBS intake.",
      ],
      ready_message: "Hi Sreelakshmi! Hope you're doing great 🤩 EduPilot update: only 3 seats left for September intake...",
    },
    {
      id: 6,
      student_id: 108,
      student_name: "Muhammed Riyas",
      program: "MBBS Georgia",
      location: "Kerala",
      fee: 220000,
      column: "replied",
      background: [
        "Successfully enrolled into Tbilisi State Medical University.",
        "Tuition fee paid directly to university bank account in Georgia.",
      ],
      ready_message: "Hi Riyas! EduPilot congratulates you on your enrollment to Tbilisi State Medical University!",
    },
    {
      id: 7,
      student_id: 101,
      student_name: "Arjun Krishnan",
      program: "MBBS Kyrgyzstan",
      location: "Kerala",
      fee: 185000,
      column: "call_scheduled",
      background: [
        "CBSE 12th Biology: 72% — well above 50% MCI requirement.",
        "Parents attended previous EduPilot webinar. High readiness to pay seat booking fee.",
      ],
      ready_message: "Hi Arjun! This is your advisor from EduPilot CRM. Document review call set!",
    },
    {
      id: 8,
      student_id: 106,
      student_name: "Rajan Thomas",
      program: "Germany Opportunity Card",
      location: "Abu Dhabi",
      fee: 92000,
      column: "call_scheduled",
      background: [
        "Completed German A1 course online — proactive and motivated candidate.",
        "5 years of IT support experience — qualifies for skilled worker Opportunity Card.",
      ],
      ready_message: "Hi Rajan! EduPilot advisory: Exciting times as your embassy interview slot approaches.",
    },
  ]);

  function moveCard(cardId: number, targetColumn: FollowupCard["column"]) {
    setCards((prev) =>
      prev.map((c) => (c.id === cardId ? { ...c, column: targetColumn } : c))
    );
  }

  const columns = [
    { key: "to_reach_today" as const, title: "To Reach Today", count: cards.filter((c) => c.column === "to_reach_today").length, fee: "₹160,000" },
    { key: "message_sent" as const, title: "Message Sent", count: cards.filter((c) => c.column === "message_sent").length, fee: "₹227,000" },
    { key: "replied" as const, title: "Replied", count: cards.filter((c) => c.column === "replied").length, fee: "₹625,000" },
    { key: "call_scheduled" as const, title: "Call Scheduled", count: cards.filter((c) => c.column === "call_scheduled").length, fee: "₹277,000" },
  ];

  return (
    <AppShell>
      <div className="space-y-6">
        {/* Header Bar */}
        <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
          <div className="flex items-center gap-3">
            <h1 className="text-2xl font-black tracking-tight text-slate-900 dark:text-white">Outreach Engine</h1>
            <span className="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700 border border-emerald-200 dark:bg-emerald-500/20 dark:text-emerald-300 dark:border-emerald-500/30">
              ⚡ 9 Students Ready
            </span>
          </div>

          <div className="flex items-center gap-3">
            {/* Date Filters */}
            <div className="flex rounded-xl border border-slate-200 bg-slate-100 p-1 text-xs font-semibold dark:border-slate-800 dark:bg-slate-900">
              <button
                onClick={() => setSelectedDate("yesterday")}
                className={`rounded-lg px-3 py-1.5 transition-all ${
                  selectedDate === "yesterday" ? "bg-gradient-to-r from-indigo-600 to-purple-600 font-bold text-white shadow-md" : "text-slate-600 dark:text-slate-400"
                }`}
              >
                Yesterday (25 Aug)
              </button>
              <button
                onClick={() => setSelectedDate("today")}
                className={`rounded-lg px-3 py-1.5 transition-all ${
                  selectedDate === "today" ? "bg-gradient-to-r from-indigo-600 to-purple-600 font-bold text-white shadow-md" : "text-slate-600 dark:text-slate-400"
                }`}
              >
                Today (26 Aug)
              </button>
              <button
                onClick={() => setSelectedDate("tomorrow")}
                className={`rounded-lg px-3 py-1.5 transition-all ${
                  selectedDate === "tomorrow" ? "bg-gradient-to-r from-indigo-600 to-purple-600 font-bold text-white shadow-md" : "text-slate-600 dark:text-slate-400"
                }`}
              >
                Tomorrow (27 Aug)
              </button>
            </div>
          </div>
        </div>

        {/* Daily Outreach Progress Widget */}
        <div className="rounded-3xl border border-slate-200/90 bg-white/90 p-5 shadow-xl shadow-slate-200/50 backdrop-blur-xl dark:border-slate-800/90 dark:bg-slate-900/80 dark:shadow-none">
          <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div className="flex items-center gap-4">
              <div className="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-tr from-indigo-600 to-purple-600 font-black text-white text-sm shadow-md">
                53%
              </div>
              <div>
                <p className="text-sm font-bold text-slate-900 dark:text-white">Today's Outreach Telemetry</p>
                <p className="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                  8 of 15 messages sent today. 7 more to reach daily goal.
                </p>
              </div>
            </div>

            <div className="flex items-center gap-3">
              <div className="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-2.5 text-center dark:border-slate-800 dark:bg-slate-950">
                <span className="block text-xs font-bold text-emerald-600 dark:text-emerald-400">5 Replied</span>
                <span className="text-[10px] text-slate-400">Conversations active</span>
              </div>
              <div className="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-2.5 text-center dark:border-slate-800 dark:bg-slate-950">
                <span className="block text-xs font-bold text-indigo-600 dark:text-indigo-400">2 Calls Scheduled</span>
                <span className="text-[10px] text-slate-400">Counseling booked</span>
              </div>
            </div>
          </div>
        </div>

        {/* Outreach Board Columns */}
        <div className="grid grid-cols-1 gap-4 lg:grid-cols-4">
          {columns.map((col) => {
            const colCards = cards.filter(
              (c) =>
                c.column === col.key &&
                (c.student_name.toLowerCase().includes(searchQuery.toLowerCase()) ||
                  c.program.toLowerCase().includes(searchQuery.toLowerCase()))
            );

            return (
              <div key={col.key} className="space-y-4">
                {/* Column Header */}
                <div className="flex items-center justify-between rounded-2xl border border-slate-200/90 bg-white/90 px-4 py-3 shadow-xl shadow-slate-200/40 backdrop-blur-xl dark:border-slate-800/90 dark:bg-slate-900/90 dark:shadow-none">
                  <div>
                    <h2 className="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">{col.title}</h2>
                    <span className="text-[11px] font-extrabold text-emerald-600 dark:text-emerald-400 font-mono">{col.fee}</span>
                  </div>
                  <span className="flex h-6 w-6 items-center justify-center rounded-xl bg-slate-100 font-bold text-slate-700 text-xs border border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700">
                    {col.count}
                  </span>
                </div>

                {/* Column Cards */}
                <div className="space-y-4">
                  {colCards.map((card) => (
                    <div
                      key={card.id}
                      className="rounded-2xl border border-slate-200/90 bg-white p-4 shadow-md backdrop-blur-md transition-all hover:border-indigo-400 dark:border-slate-800/90 dark:bg-slate-900 dark:shadow-none"
                    >
                      {/* Program & Location Badges */}
                      <div className="flex items-center justify-between text-[10px] font-bold">
                        <span className="rounded-md bg-indigo-50 px-2 py-0.5 text-indigo-700 border border-indigo-200 dark:bg-indigo-500/20 dark:text-indigo-300 dark:border-indigo-500/30">
                          {card.program}
                        </span>
                        <span className="rounded-md bg-slate-100 px-2 py-0.5 text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                          📍 {card.location}
                        </span>
                      </div>

                      {/* Student Name */}
                      <h3 className="mt-3 text-sm font-bold text-slate-900 dark:text-white">{card.student_name}</h3>
                      <p className="text-[10px] font-medium text-slate-500 dark:text-slate-400">🎯 {card.program}</p>

                      {/* Eligibility & Background Box */}
                      <div className="mt-3 rounded-xl border border-indigo-100 bg-indigo-50/70 p-3 text-[11px] dark:border-indigo-500/20 dark:bg-indigo-500/10">
                        <p className="font-extrabold text-indigo-900 dark:text-indigo-300 uppercase text-[9px] tracking-wider mb-1">
                          🪪 ELIGIBILITY & BACKGROUND:
                        </p>
                        <ul className="space-y-1 text-slate-700 dark:text-slate-300 list-disc list-inside leading-snug">
                          {card.background.map((bg, idx) => (
                            <li key={idx}>{bg}</li>
                          ))}
                        </ul>
                      </div>

                      {/* Ready WhatsApp Message Box */}
                      <div className="mt-3 rounded-xl border border-slate-200 bg-slate-50 p-3 text-[11px] dark:border-slate-800 dark:bg-slate-950">
                        <div className="flex items-center justify-between font-bold text-slate-500 uppercase text-[9px] tracking-wider mb-1">
                          <span>READY WHATSAPP MESSAGE</span>
                          <span className="cursor-pointer text-indigo-600 dark:text-indigo-400">✏️</span>
                        </div>
                        <p className="italic text-slate-800 dark:text-slate-300 leading-snug font-mono text-[10px]">
                          "{card.ready_message}"
                        </p>
                      </div>

                      {/* Bottom Action Row */}
                      <div className="mt-4 flex items-center justify-between border-t border-slate-100 pt-3 dark:border-slate-800/80">
                        <span className="text-xs font-black text-emerald-600 dark:text-emerald-400 font-mono">
                          ₹{card.fee.toLocaleString()}
                        </span>

                        {col.key === "to_reach_today" ? (
                          <button
                            onClick={() => moveCard(card.id, "message_sent")}
                            className="rounded-xl bg-emerald-500 px-3.5 py-1.5 text-xs font-bold text-white shadow-md hover:bg-emerald-600"
                          >
                            Reach Out
                          </button>
                        ) : col.key === "message_sent" ? (
                          <button
                            onClick={() => moveCard(card.id, "replied")}
                            className="rounded-xl border border-amber-300 bg-amber-50 px-3.5 py-1.5 text-xs font-bold text-amber-700 hover:bg-amber-100 dark:border-amber-500/30 dark:bg-amber-500/20 dark:text-amber-300"
                          >
                            Mark Replied
                          </button>
                        ) : col.key === "replied" ? (
                          <button
                            onClick={() => moveCard(card.id, "call_scheduled")}
                            className="rounded-xl border border-indigo-300 bg-indigo-50 px-3.5 py-1.5 text-xs font-bold text-indigo-700 hover:bg-indigo-100 dark:border-indigo-500/30 dark:bg-indigo-500/20 dark:text-indigo-300"
                          >
                            Schedule Call
                          </button>
                        ) : (
                          <span className="rounded-xl bg-slate-900 px-3.5 py-1.5 text-xs font-bold text-white dark:bg-slate-800 dark:text-slate-300">
                            Call Booked
                          </span>
                        )}
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            );
          })}
        </div>
      </div>
    </AppShell>
  );
}
