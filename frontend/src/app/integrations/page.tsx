"use client";

import { useState } from "react";
import AppShell from "@/components/AppShell";
import { GoldIntegration, MOCK_INTEGRATIONS } from "@/lib/api";

export default function IntegrationsPage() {
  const [integrations, setIntegrations] = useState<GoldIntegration[]>(MOCK_INTEGRATIONS);

  return (
    <AppShell>
      <div className="space-y-6">
        {/* Header */}
        <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
          <div>
            <div className="flex items-center gap-2">
              <span className="rounded-full bg-indigo-100 px-2.5 py-0.5 text-[9px] font-extrabold text-indigo-700 border border-indigo-200 uppercase tracking-wider dark:bg-indigo-500/20 dark:text-indigo-300 dark:border-indigo-500/30">
                Gold Integration Engine
              </span>
            </div>
            <h1 className="mt-1 text-2xl font-black tracking-tight text-slate-900 dark:text-white">
              Social & Web Integrations Webhook Hub
            </h1>
            <p className="mt-0.5 text-xs font-medium text-slate-500 dark:text-slate-400">
              Connect Meta WhatsApp Business, Facebook Lead Ads, Instagram DMs, and Website Webhooks.
            </p>
          </div>

          <button
            onClick={() => alert("Connecting new provider...")}
            className="flex items-center gap-2 rounded-2xl bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 px-4 py-2.5 text-xs font-bold text-white shadow-lg hover:opacity-95 transition-all"
          >
            <span>+</span>
            <span>Connect Channel</span>
          </button>
        </div>

        {/* Integration Cards */}
        <div className="grid grid-cols-1 gap-4 sm:grid-cols-3">
          {integrations.map((ig) => (
            <div key={ig.id} className="rounded-3xl border border-slate-200/90 bg-white/90 p-5 shadow-xl shadow-slate-200/50 backdrop-blur-xl dark:border-slate-800/90 dark:bg-slate-900/80 dark:shadow-none space-y-3">
              <div className="flex items-center justify-between">
                <span className="flex h-10 w-10 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700 font-bold text-sm dark:bg-indigo-500/20 dark:text-indigo-300">
                  ⚡
                </span>
                <span className="rounded-full bg-emerald-100 px-2.5 py-0.5 text-[9px] font-bold text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30">
                  {ig.status}
                </span>
              </div>

              <div>
                <h3 className="text-sm font-bold text-slate-900 dark:text-white">{ig.name}</h3>
                <p className="text-[10px] text-slate-500 dark:text-slate-400 uppercase tracking-wider font-extrabold mt-0.5">
                  Type: {ig.type}
                </p>
              </div>

              <div className="border-t border-slate-100 pt-3 text-[10px] text-slate-400 flex items-center justify-between dark:border-slate-800">
                <span>Last Synced: {ig.last_synced_at}</span>
                <span className="cursor-pointer text-indigo-600 font-bold dark:text-indigo-400">Configure</span>
              </div>
            </div>
          ))}
        </div>
      </div>
    </AppShell>
  );
}
