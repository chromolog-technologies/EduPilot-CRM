"use client";

import { useEffect, useState } from "react";
import AppShell from "@/components/AppShell";
import { api, DashboardSummary, MOCK_COUNSELORS } from "@/lib/api";

export default function DashboardPage() {
  const [summary, setSummary] = useState<DashboardSummary | null>(null);

  useEffect(() => {
    const token = typeof window !== "undefined" ? localStorage.getItem("bv_token") : null;
    if (token) {
      api<DashboardSummary>("/dashboard/summary", {}, token)
        .then((res) => setSummary(res.data))
        .catch(() => setSummary(null));
    }
  }, []);

  const enrolledRevenue = summary?.enrolled_revenue_this_month ?? 220000;
  const activePipelineFees = summary?.active_pipeline_fees ?? 1424000;
  const enrollmentRate = summary?.enrollment_rate ?? 44.5;
  const avgResponseTime = summary?.avg_response_time_mins ?? 12;
  const counselors = summary?.counselors ?? MOCK_COUNSELORS;

  const programStreams = [
    { program: "MBBS – Kyrgyzstan", revenue: 420000, students: 11, deadline: "12 days left", progress: 85, color: "from-indigo-500 to-purple-500" },
    { program: "MBBS – Georgia", revenue: 385000, students: 9, deadline: "18 days left", progress: 75, color: "from-cyan-500 to-blue-600" },
    { program: "Germany Opportunity Card", revenue: 260000, students: 6, deadline: "25 days left", progress: 55, color: "from-emerald-500 to-teal-600" },
    { program: "MBBS – Uzbekistan", revenue: 190000, students: 4, deadline: "8 days left", progress: 40, color: "from-amber-500 to-rose-500" },
    { program: "Engineering – Georgia", revenue: 140000, students: 3, deadline: "30 days left", progress: 30, color: "from-pink-500 to-purple-600" },
  ];

  const timelineEvents = [
    { id: 1, type: "verified", title: "Passport & 12th Marksheet Verified", student: "Arjun Krishnan (MBBS Kyrgyzstan)", time: "10 mins ago", icon: "✓" },
    { id: 2, type: "call", title: "Counseling Video Call Completed", student: "Rajan Thomas (Germany Opportunity)", time: "35 mins ago", icon: "📞" },
    { id: 3, type: "applied", title: "Dossier Submitted to Tbilisi University", student: "Fathima Noushad (MBBS Georgia)", time: "1 hour ago", icon: "📄" },
    { id: 4, type: "inquiry", title: "New Instagram DM Inquiry", student: "Akhil Suresh (MBBS Uzbekistan)", time: "2 hours ago", icon: "💬" },
  ];

  return (
    <AppShell>
      <div className="space-y-6">
        {/* HERO COMMAND BANNER & AI TICKER */}
        <div className="relative overflow-hidden rounded-3xl border border-indigo-200/80 bg-white/90 p-6 shadow-xl shadow-slate-200/60 backdrop-blur-2xl dark:border-indigo-500/30 dark:bg-slate-900/90 dark:shadow-none">
          <div className="absolute top-0 right-0 h-48 w-48 -translate-y-12 translate-x-12 rounded-full bg-indigo-500/10 blur-3xl" />
          
          <div className="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            {/* Left: Brand & Conversion Arc */}
            <div className="flex items-center gap-5">
              {/* Radial Conversion Arc Gauge */}
              <div className="relative flex h-20 w-20 flex-shrink-0 items-center justify-center rounded-2xl bg-indigo-50 border border-indigo-200 font-bold text-indigo-700 shadow-inner dark:bg-slate-950 dark:border-indigo-500/30 dark:text-white">
                <svg className="h-16 w-16 -rotate-90 transform" viewBox="0 0 36 36">
                  <path
                    className="text-slate-200 dark:text-slate-800"
                    strokeWidth="3.5"
                    stroke="currentColor"
                    fill="none"
                    d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                  />
                  <path
                    className="text-indigo-600 dark:text-indigo-400"
                    strokeDasharray="44.5, 100"
                    strokeWidth="3.5"
                    strokeLinecap="round"
                    stroke="currentColor"
                    fill="none"
                    d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                  />
                </svg>
                <span className="absolute text-xs font-black font-mono">11%</span>
              </div>

              <div>
                <div className="flex items-center gap-2">
                  <span className="rounded-full bg-indigo-100 px-2.5 py-0.5 text-[9px] font-extrabold text-indigo-700 border border-indigo-200 uppercase tracking-wider dark:bg-indigo-500/20 dark:text-indigo-300 dark:border-indigo-500/30">
                    EduPilot Command Deck
                  </span>
                  <span className="flex items-center gap-1 text-[10px] font-bold text-emerald-600 dark:text-emerald-400">
                    <span className="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-ping" />
                    LIVE ADMISSIONS RADAR
                  </span>
                </div>
                <h1 className="mt-1 text-2xl font-black tracking-tight text-slate-900 dark:text-white">
                  Monthly Target: ₹220,000 / <span className="text-indigo-600 dark:text-indigo-400">₹2.0M</span>
                </h1>
                <p className="mt-0.5 text-xs text-slate-600 dark:text-slate-400 font-medium">
                  Active Admissions Pipeline: <span className="font-extrabold text-slate-900 dark:text-white">₹{activePipelineFees.toLocaleString()}</span> across 9 active student dossiers.
                </p>
              </div>
            </div>

            {/* Right: Quick Command Triggers */}
            <div className="flex items-center gap-3">
              <button
                onClick={() => {
                  const modalBtn = document.querySelector<HTMLButtonElement>('button:has(span:contains("Add Student"))');
                  modalBtn?.click();
                }}
                className="flex items-center gap-2 rounded-2xl bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 px-5 py-2.5 text-xs font-bold text-white shadow-lg hover:opacity-95 transition-all"
              >
                <span>+</span>
                <span>Instant Inquiry</span>
              </button>

              <button
                onClick={() => alert("Launching EduPilot Call Campaign...")}
                className="flex items-center gap-2 rounded-2xl border border-slate-200 bg-slate-100 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-200 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800 transition-colors"
              >
                <span>📞</span>
                <span>Call Campaign</span>
              </button>
            </div>
          </div>

          {/* AI Insights Live Ticker Bar */}
          <div className="mt-4 flex items-center gap-3 rounded-2xl border border-indigo-200 bg-indigo-50/80 px-4 py-2 text-xs dark:border-indigo-500/20 dark:bg-indigo-500/10">
            <span className="flex h-6 items-center gap-1 rounded-lg bg-indigo-600 px-2 text-[10px] font-extrabold text-white uppercase">
              ⚡ AI INSIGHT:
            </span>
            <p className="line-clamp-1 text-[11px] font-semibold text-indigo-900 dark:text-indigo-200">
              4 Hot leads in Kyrgyzstan & Georgia MBBS require advisor outreach within 15 mins to maintain 85% enrollment conversion probability.
            </p>
          </div>
        </div>

        {/* 4 TELEMETRY MATRIX TILES */}
        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
          {/* Tile 1: Revenue Forecast Ring */}
          <div className="rounded-3xl border border-slate-200/90 bg-white/90 p-5 shadow-xl shadow-slate-200/50 backdrop-blur-xl dark:border-slate-800/90 dark:bg-slate-900/80 dark:shadow-none">
            <div className="flex items-center justify-between">
              <span className="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">
                REVENUE FORECAST
              </span>
              <span className="rounded-full bg-emerald-100 px-2 py-0.5 text-[9px] font-bold text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400">
                +14.2% pace
              </span>
            </div>
            <p className="mt-3 text-3xl font-black tracking-tight text-slate-900 dark:text-white">
              ₹{enrolledRevenue.toLocaleString()}
            </p>
            <div className="mt-2 h-1.5 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-950">
              <div className="h-full rounded-full bg-gradient-to-r from-emerald-400 to-teal-500" style={{ width: '45%' }} />
            </div>
            <p className="mt-2 text-[10px] font-semibold text-slate-500">Active Pipeline: ₹1.42M pending</p>
          </div>

          {/* Tile 2: Advisor Speedometer Dial */}
          <div className="rounded-3xl border border-slate-200/90 bg-white/90 p-5 shadow-xl shadow-slate-200/50 backdrop-blur-xl dark:border-slate-800/90 dark:bg-slate-900/80 dark:shadow-none">
            <div className="flex items-center justify-between">
              <span className="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">
                RESPONSE SPEEDOMETER
              </span>
              <span className="rounded-full bg-indigo-100 px-2 py-0.5 text-[9px] font-bold text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300">
                ⚡ Ultra Fast
              </span>
            </div>
            <p className="mt-3 text-3xl font-black text-purple-600 dark:text-purple-300 tracking-tight">
              {avgResponseTime} <span className="text-xs text-slate-400 font-normal">mins avg</span>
            </p>
            <div className="mt-2 h-1.5 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-950">
              <div className="h-full rounded-full bg-gradient-to-r from-purple-500 to-indigo-500" style={{ width: '85%' }} />
            </div>
            <p className="mt-2 text-[10px] font-semibold text-slate-500">WhatsApp response velocity</p>
          </div>

          {/* Tile 3: Intake Conversion Ratio */}
          <div className="rounded-3xl border border-slate-200/90 bg-white/90 p-5 shadow-xl shadow-slate-200/50 backdrop-blur-xl dark:border-slate-800/90 dark:bg-slate-900/80 dark:shadow-none">
            <div className="flex items-center justify-between">
              <span className="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">
                INTAKE CONVERSION RATIO
              </span>
              <span className="rounded-full bg-cyan-100 px-2 py-0.5 text-[9px] font-bold text-cyan-700 dark:bg-cyan-500/20 dark:text-cyan-300">
                Top 5%
              </span>
            </div>
            <p className="mt-3 text-3xl font-black text-emerald-600 dark:text-emerald-400 tracking-tight">
              {enrollmentRate}%
            </p>
            <div className="mt-2 h-1.5 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-950">
              <div className="h-full rounded-full bg-gradient-to-r from-cyan-400 to-emerald-400" style={{ width: '70%' }} />
            </div>
            <p className="mt-2 text-[10px] font-semibold text-slate-500">Benchmark: 35% standard</p>
          </div>

          {/* Tile 4: Dossier Verification Index */}
          <div className="rounded-3xl border border-slate-200/90 bg-white/90 p-5 shadow-xl shadow-slate-200/50 backdrop-blur-xl dark:border-slate-800/90 dark:bg-slate-900/80 dark:shadow-none">
            <div className="flex items-center justify-between">
              <span className="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">
                DOSSIER READINESS INDEX
              </span>
              <span className="rounded-full bg-amber-100 px-2 py-0.5 text-[9px] font-bold text-amber-700 dark:bg-amber-500/20 dark:text-amber-300">
                88% Complete
              </span>
            </div>
            <p className="mt-3 text-3xl font-black text-slate-900 dark:text-white tracking-tight">
              21 / 25 <span className="text-xs text-slate-400 font-normal">verified</span>
            </p>
            <div className="mt-2 h-1.5 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-950">
              <div className="h-full rounded-full bg-gradient-to-r from-amber-400 to-rose-500" style={{ width: '88%' }} />
            </div>
            <p className="mt-2 text-[10px] font-semibold text-slate-500">Marksheets & NEET scorecards</p>
          </div>
        </div>

        {/* MAIN SECTION: ADVISOR INTELLIGENCE GRID & LIVE PROGRAM STREAMS */}
        <div className="grid grid-cols-1 gap-6 lg:grid-cols-12">
          {/* Left Column: Advisor Intelligence Grid (8 COLS) */}
          <div className="lg:col-span-8 space-y-4">
            <div className="flex items-center justify-between">
              <div>
                <h2 className="text-base font-bold text-slate-900 dark:text-white">Advisor Intelligence Grid</h2>
                <p className="text-[11px] text-slate-500 dark:text-slate-400">Advisor performance telemetry & caseload matrix</p>
              </div>
              <span className="rounded-xl border border-slate-200 bg-slate-100 px-3 py-1 text-xs font-bold text-indigo-600 dark:border-slate-800 dark:bg-slate-950 dark:text-indigo-400">
                August 2026 Shift
              </span>
            </div>

            {/* Grid of Advisor Cards */}
            <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
              {counselors.map((c) => (
                <div
                  key={c.id}
                  className="rounded-3xl border border-slate-200/90 bg-white/90 p-5 shadow-xl shadow-slate-200/50 backdrop-blur-xl transition-all hover:border-indigo-400/50 dark:border-slate-800/90 dark:bg-slate-900/80 dark:shadow-none"
                >
                  <div className="flex items-start justify-between">
                    <div className="flex items-center gap-3">
                      <div className="relative">
                        <div className="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-tr from-indigo-600 to-purple-600 font-extrabold text-white text-sm shadow-md">
                          {c.name.charAt(0)}
                        </div>
                        <span className="absolute -bottom-1 -right-1 h-3.5 w-3.5 rounded-full border-2 border-white bg-emerald-500 dark:border-slate-900" />
                      </div>
                      <div>
                        <h3 className="text-sm font-bold text-slate-900 dark:text-white">{c.name}</h3>
                        <p className="text-[10px] text-slate-500 dark:text-slate-400 font-medium line-clamp-1">{c.role}</p>
                      </div>
                    </div>

                    <span className="rounded-full bg-emerald-100 px-2.5 py-0.5 text-[9px] font-bold text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30">
                      {c.status}
                    </span>
                  </div>

                  <div className="mt-4 grid grid-cols-3 gap-2 rounded-2xl border border-slate-100 bg-slate-50 p-3 text-center text-xs dark:border-slate-800 dark:bg-slate-950/80">
                    <div>
                      <span className="block text-[9px] font-bold text-slate-400 uppercase">STUDENTS</span>
                      <span className="font-extrabold text-slate-900 dark:text-white">{c.assigned_students_count}</span>
                    </div>
                    <div>
                      <span className="block text-[9px] font-bold text-slate-400 uppercase">RESPONSE</span>
                      <span className="font-extrabold text-purple-600 dark:text-purple-300 font-mono">{c.avg_response_mins}m</span>
                    </div>
                    <div>
                      <span className="block text-[9px] font-bold text-slate-400 uppercase">CONVERT</span>
                      <span className="font-extrabold text-emerald-600 dark:text-emerald-400">{c.enrollment_rate}%</span>
                    </div>
                  </div>

                  <div className="mt-4 flex items-center justify-between border-t border-slate-100 pt-3 dark:border-slate-800/80">
                    <span className="text-[10px] font-bold text-slate-500">Caseload: High</span>
                    <button
                      onClick={() => alert(`Direct WhatsApp message to ${c.name}`)}
                      className="rounded-xl bg-indigo-50 border border-indigo-200 px-3 py-1 text-[10px] font-bold text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-500/20 dark:border-indigo-500/30 dark:text-indigo-300 transition-colors"
                    >
                      💬 Message Advisor
                    </button>
                  </div>
                </div>
              ))}
            </div>
          </div>

          {/* Right Column: Live Program Streams & AI Sentinel (4 COLS) */}
          <div className="lg:col-span-4 space-y-4">
            <div className="flex items-center justify-between">
              <h2 className="text-base font-bold text-slate-900 dark:text-white">Live Program Streams</h2>
              <span className="rounded-full bg-emerald-100 px-2.5 py-0.5 text-[9px] font-bold text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300">
                Active
              </span>
            </div>

            <div className="space-y-3">
              {programStreams.map((p) => (
                <div
                  key={p.program}
                  className="rounded-3xl border border-slate-200/90 bg-white/90 p-4 shadow-xl shadow-slate-200/50 backdrop-blur-xl dark:border-slate-800/90 dark:bg-slate-900/80 dark:shadow-none"
                >
                  <div className="flex items-center justify-between text-xs font-bold">
                    <span className="text-slate-900 dark:text-white">{p.program}</span>
                    <span className="font-mono text-emerald-600 dark:text-emerald-400">₹{p.revenue.toLocaleString()}</span>
                  </div>

                  <div className="mt-2 flex items-center justify-between text-[10px] text-slate-500 dark:text-slate-400 font-medium">
                    <span>👥 {p.students} Students</span>
                    <span className="text-amber-600 dark:text-amber-400 font-semibold">⏳ {p.deadline}</span>
                  </div>

                  <div className="mt-2.5 h-2 w-full overflow-hidden rounded-full bg-slate-100 border border-slate-200 dark:bg-slate-950 dark:border-slate-800">
                    <div
                      className={`h-full rounded-full bg-gradient-to-r ${p.color} transition-all duration-500`}
                      style={{ width: `${p.progress}%` }}
                    />
                  </div>
                </div>
              ))}
            </div>

            {/* AI Operations Sentinel Timeline */}
            <div className="rounded-3xl border border-slate-200/90 bg-white/90 p-5 shadow-xl shadow-slate-200/50 backdrop-blur-xl dark:border-slate-800/90 dark:bg-slate-900/80 dark:shadow-none">
              <div className="flex items-center justify-between border-b border-slate-100 pb-3 dark:border-slate-800">
                <h3 className="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">AI Operations Sentinel</h3>
                <span className="text-[10px] font-bold text-emerald-600 dark:text-emerald-400">100% Verified</span>
              </div>

              <div className="mt-3 space-y-3">
                {timelineEvents.map((evt) => (
                  <div key={evt.id} className="flex items-start gap-3 rounded-2xl border border-slate-100 bg-slate-50 p-3 text-xs dark:border-slate-800 dark:bg-slate-950">
                    <span className="flex h-7 w-7 items-center justify-center rounded-xl bg-indigo-100 font-bold text-indigo-700 text-xs dark:bg-indigo-500/20 dark:text-indigo-300">
                      {evt.icon}
                    </span>
                    <div>
                      <p className="font-bold text-slate-900 dark:text-white text-[11px]">{evt.title}</p>
                      <p className="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">{evt.student}</p>
                      <span className="text-[9px] font-mono text-slate-400">{evt.time}</span>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>
      </div>
    </AppShell>
  );
}
