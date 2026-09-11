"use client";

import { useState } from "react";
import AppShell from "@/components/AppShell";
import { MOCK_COUNSELORS } from "@/lib/api";

export default function AdmissionsReportsPage() {
  const [fromDate, setFromDate] = useState("2026-08-01");
  const [toDate, setToDate] = useState("2026-08-31");
  const [selectedCounselor, setSelectedCounselor] = useState("All Counselors");

  const sources = [
    { name: "WhatsApp Direct", count: 480, converted: 215, rate: "44.8%", revenue: "₹39,775,000" },
    { name: "Instagram Reels", count: 320, converted: 142, rate: "44.3%", revenue: "₹26,270,000" },
    { name: "Website Inquiry Form", count: 210, converted: 98, rate: "46.6%", revenue: "₹18,130,000" },
    { name: "Referrals (Alumni / NRI)", count: 140, converted: 82, rate: "58.5%", revenue: "₹15,170,000" },
    { name: "Facebook Ads", count: 100, converted: 35, rate: "35.0%", revenue: "₹6,475,000" },
  ];

  const stagesDistribution = [
    { stage: "New Inquiry", count: 82, percent: "15%" },
    { stage: "First Outreach", count: 110, percent: "20%" },
    { stage: "Follow-up", count: 95, percent: "17%" },
    { stage: "Counseling Meeting", count: 125, percent: "23%" },
    { stage: "Docs Collecting", count: 68, percent: "12%" },
    { stage: "Applied", count: 42, percent: "8%" },
    { stage: "Payment & Enrolled", count: 28, percent: "5%" },
  ];

  return (
    <AppShell>
      <div className="space-y-6 font-sans">
        {/* Page Header */}
        <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
          <div>
            <div className="flex items-center gap-2">
              <span className="rounded-full bg-indigo-100 px-2.5 py-0.5 text-[9px] font-extrabold text-indigo-700 border border-indigo-200 uppercase tracking-wider dark:bg-indigo-500/20 dark:text-indigo-300 dark:border-indigo-500/30">
                EduPilot Telemetry
              </span>
            </div>
            <h1 className="mt-1 text-2xl font-black tracking-tight text-slate-900 dark:text-white">
              Intelligence & Reports Analytics
            </h1>
            <p className="mt-0.5 text-xs font-medium text-slate-500 dark:text-slate-400">
              Detailed report breakdown across acquisition channels, advisor conversion velocity, and revenue projections.
            </p>
          </div>

          <button
            onClick={() => alert("Exporting EduPilot Analytics PDF...")}
            className="flex items-center gap-2 rounded-2xl bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 px-4 py-2 text-xs font-bold text-white shadow-lg hover:opacity-95 transition-all"
          >
            <span>📄</span>
            <span>Export Analytics (PDF)</span>
          </button>
        </div>

        {/* Filters Bar */}
        <div className="flex flex-wrap items-center justify-between gap-4 rounded-3xl border border-slate-200/90 bg-white/90 p-4 shadow-xl shadow-slate-200/50 backdrop-blur-xl text-xs font-semibold text-slate-700 dark:border-slate-800/90 dark:bg-slate-900/80 dark:shadow-none dark:text-slate-300">
          <div className="flex flex-wrap items-center gap-4">
            <div className="flex items-center gap-2">
              <span className="text-slate-400">From:</span>
              <input
                type="date"
                value={fromDate}
                onChange={(e) => setFromDate(e.target.value)}
                className="rounded-xl border border-slate-200 bg-slate-50 px-3 py-1.5 font-medium text-slate-900 dark:border-slate-800 dark:bg-slate-950 dark:text-white"
              />
            </div>
            <div className="flex items-center gap-2">
              <span className="text-slate-400">To:</span>
              <input
                type="date"
                value={toDate}
                onChange={(e) => setToDate(e.target.value)}
                className="rounded-xl border border-slate-200 bg-slate-50 px-3 py-1.5 font-medium text-slate-900 dark:border-slate-800 dark:bg-slate-950 dark:text-white"
              />
            </div>

            <div className="flex items-center gap-2">
              <span className="text-slate-400">Advisor:</span>
              <select
                value={selectedCounselor}
                onChange={(e) => setSelectedCounselor(e.target.value)}
                className="rounded-xl border border-slate-200 bg-slate-50 px-3 py-1.5 text-slate-900 dark:border-slate-800 dark:bg-slate-950 dark:text-white"
              >
                <option>All Counselors</option>
                {MOCK_COUNSELORS.map((c) => (
                  <option key={c.id} value={c.name}>
                    {c.name}
                  </option>
                ))}
              </select>
            </div>
          </div>

          <button
            onClick={() => alert("Filters applied")}
            className="rounded-xl border border-slate-200 bg-slate-100 px-4 py-2 font-bold text-slate-800 hover:bg-slate-200 dark:border-slate-800 dark:bg-slate-950 dark:text-white dark:hover:bg-slate-800"
          >
            Apply Telemetry Filters
          </button>
        </div>

        {/* Top Summary Metrics */}
        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
          <div className="rounded-3xl border border-slate-200/90 bg-white/90 p-5 shadow-xl shadow-slate-200/50 backdrop-blur-xl dark:border-slate-800/90 dark:bg-slate-900/80 dark:shadow-none">
            <p className="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Inquiries Received</p>
            <p className="mt-3 text-3xl font-black text-slate-900 dark:text-white">1,250</p>
            <p className="mt-1 text-[11px] font-bold text-emerald-600 dark:text-emerald-400">↑ +18% vs last month</p>
          </div>
          <div className="rounded-3xl border border-slate-200/90 bg-white/90 p-5 shadow-xl shadow-slate-200/50 backdrop-blur-xl dark:border-slate-800/90 dark:bg-slate-900/80 dark:shadow-none">
            <p className="text-xs font-bold text-slate-400 uppercase tracking-wider">Overall Conversion Rate</p>
            <p className="mt-3 text-3xl font-black text-emerald-600 dark:text-emerald-400">44.5%</p>
            <p className="mt-1 text-[11px] font-semibold text-slate-400">Industry benchmark: 35%</p>
          </div>
          <div className="rounded-3xl border border-slate-200/90 bg-white/90 p-5 shadow-xl shadow-slate-200/50 backdrop-blur-xl dark:border-slate-800/90 dark:bg-slate-900/80 dark:shadow-none">
            <p className="text-xs font-bold text-slate-400 uppercase tracking-wider">Avg Consulting Fee</p>
            <p className="mt-3 text-3xl font-black text-slate-900 dark:text-white font-mono">₹182,000</p>
            <p className="mt-1 text-[11px] font-semibold text-slate-400">Across MBBS & Engineering</p>
          </div>
          <div className="rounded-3xl border border-slate-200/90 bg-white/90 p-5 shadow-xl shadow-slate-200/50 backdrop-blur-xl dark:border-slate-800/90 dark:bg-slate-900/80 dark:shadow-none">
            <p className="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Program Revenue</p>
            <p className="mt-3 text-3xl font-black text-indigo-600 dark:text-indigo-400 font-mono">₹105.8M</p>
            <p className="mt-1 text-[11px] font-bold text-emerald-600 dark:text-emerald-400">↑ 100% Target Met</p>
          </div>
        </div>

        {/* Lead Source Breakdown Table */}
        <div className="rounded-3xl border border-slate-200/90 bg-white/90 p-6 shadow-xl shadow-slate-200/50 backdrop-blur-xl dark:border-slate-800/90 dark:bg-slate-900/80 dark:shadow-none">
          <h2 className="text-base font-bold text-slate-900 dark:text-white mb-4">Lead Source Acquisition Performance</h2>
          <div className="overflow-x-auto">
            <table className="w-full text-left text-xs">
              <thead>
                <tr className="border-b border-slate-100 bg-slate-50/50 text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:border-slate-800 dark:bg-slate-950/60">
                  <th className="py-4 px-4">Lead Channel</th>
                  <th className="py-4 px-4">Total Inquiries</th>
                  <th className="py-4 px-4">Enrolled Students</th>
                  <th className="py-4 px-4">Conversion Rate</th>
                  <th className="py-4 px-4">Total Revenue Value</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-100 font-medium text-slate-700 dark:divide-slate-800/60 dark:text-slate-300">
                {sources.map((src) => (
                  <tr key={src.name} className="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                    <td className="py-4 px-4 font-bold text-slate-900 dark:text-white">{src.name}</td>
                    <td className="py-4 px-4">{src.count}</td>
                    <td className="py-4 px-4 font-bold text-emerald-600 dark:text-emerald-400">{src.converted}</td>
                    <td className="py-4 px-4 font-bold text-slate-900 dark:text-white">{src.rate}</td>
                    <td className="py-4 px-4 font-extrabold text-emerald-600 dark:text-emerald-400 font-mono">{src.revenue}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </AppShell>
  );
}
