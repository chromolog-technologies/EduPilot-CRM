"use client";

import { useState } from "react";
import AppShell from "@/components/AppShell";
import { GoldCampaign, MOCK_CAMPAIGNS } from "@/lib/api";

export default function CampaignsPage() {
  const [campaigns, setCampaigns] = useState<GoldCampaign[]>(MOCK_CAMPAIGNS);
  const [showAddModal, setShowAddModal] = useState(false);
  const [newCampaign, setNewCampaign] = useState({
    name: "",
    code: "",
    platform: "facebook" as const,
    budget: 50000,
  });

  function handleCreateCampaign() {
    if (!newCampaign.name.trim()) return;

    const created: GoldCampaign = {
      id: Date.now(),
      name: newCampaign.name,
      code: newCampaign.code || `CMP-${Date.now().toString().slice(-4)}`,
      platform: newCampaign.platform,
      budget: Number(newCampaign.budget),
      leads_generated: 0,
      conversions: 0,
      status: "active",
    };

    setCampaigns((prev) => [created, ...prev]);
    setShowAddModal(false);
    setNewCampaign({ name: "", code: "", platform: "facebook", budget: 50000 });
  }

  return (
    <AppShell>
      <div className="space-y-6">
        {/* Header */}
        <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
          <div>
            <div className="flex items-center gap-2">
              <span className="rounded-full bg-indigo-100 px-2.5 py-0.5 text-[9px] font-extrabold text-indigo-700 border border-indigo-200 uppercase tracking-wider dark:bg-indigo-500/20 dark:text-indigo-300 dark:border-indigo-500/30">
                Gold Feature
              </span>
            </div>
            <h1 className="mt-1 text-2xl font-black tracking-tight text-slate-900 dark:text-white">
              Campaign Management & Marketing ROI
            </h1>
            <p className="mt-0.5 text-xs font-medium text-slate-500 dark:text-slate-400">
              Track campaign performance, ad spend allocation, and lead conversion rates across channels.
            </p>
          </div>

          <button
            onClick={() => setShowAddModal(true)}
            className="flex items-center gap-2 rounded-2xl bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 px-4 py-2.5 text-xs font-bold text-white shadow-lg hover:opacity-95 transition-all"
          >
            <span>+</span>
            <span>New Campaign</span>
          </button>
        </div>

        {/* Campaign Metric Tiles */}
        <div className="grid grid-cols-1 gap-4 sm:grid-cols-3">
          <div className="rounded-3xl border border-slate-200/90 bg-white/90 p-5 shadow-xl shadow-slate-200/50 backdrop-blur-xl dark:border-slate-800/90 dark:bg-slate-900/80 dark:shadow-none">
            <span className="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">TOTAL CAMPAIGN BUDGET</span>
            <p className="mt-2 text-3xl font-black text-slate-900 dark:text-white font-mono">₹110,000</p>
            <span className="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold mt-1 inline-block">Active 3 Campaigns</span>
          </div>

          <div className="rounded-3xl border border-slate-200/90 bg-white/90 p-5 shadow-xl shadow-slate-200/50 backdrop-blur-xl dark:border-slate-800/90 dark:bg-slate-900/80 dark:shadow-none">
            <span className="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">LEADS GENERATED</span>
            <p className="mt-2 text-3xl font-black text-indigo-600 dark:text-indigo-400">101 Leads</p>
            <span className="text-[10px] text-slate-500 font-semibold mt-1 inline-block">Cost per Lead: ₹1,089</span>
          </div>

          <div className="rounded-3xl border border-slate-200/90 bg-white/90 p-5 shadow-xl shadow-slate-200/50 backdrop-blur-xl dark:border-slate-800/90 dark:bg-slate-900/80 dark:shadow-none">
            <span className="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">ENROLLED CONVERSIONS</span>
            <p className="mt-2 text-3xl font-black text-emerald-600 dark:text-emerald-400">44 Students</p>
            <span className="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold mt-1 inline-block">43.5% Conversion Rate</span>
          </div>
        </div>

        {/* Campaign Data Table */}
        <div className="overflow-hidden rounded-3xl border border-slate-200/90 bg-white/90 shadow-xl shadow-slate-200/50 backdrop-blur-xl dark:border-slate-800/90 dark:bg-slate-900/80 dark:shadow-none">
          <div className="overflow-x-auto">
            <table className="w-full text-left text-xs">
              <thead>
                <tr className="border-b border-slate-100 bg-slate-50/50 text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:border-slate-800 dark:bg-slate-950/60">
                  <th className="py-4 px-4">Campaign Name</th>
                  <th className="py-4 px-4">Code</th>
                  <th className="py-4 px-4">Platform</th>
                  <th className="py-4 px-4">Budget (₹)</th>
                  <th className="py-4 px-4">Leads</th>
                  <th className="py-4 px-4">Enrolled</th>
                  <th className="py-4 px-4">Status</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-100 font-medium text-slate-700 dark:divide-slate-800/60 dark:text-slate-300">
                {campaigns.map((cmp) => (
                  <tr key={cmp.id} className="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                    <td className="py-4 px-4 font-bold text-slate-900 dark:text-white">{cmp.name}</td>
                    <td className="py-4 px-4 font-mono text-indigo-600 dark:text-indigo-400">{cmp.code}</td>
                    <td className="py-4 px-4 uppercase text-[10px] font-extrabold text-slate-500">{cmp.platform}</td>
                    <td className="py-4 px-4 font-bold font-mono">₹{cmp.budget.toLocaleString()}</td>
                    <td className="py-4 px-4 font-bold text-slate-900 dark:text-white">{cmp.leads_generated}</td>
                    <td className="py-4 px-4 font-bold text-emerald-600 dark:text-emerald-400">{cmp.conversions}</td>
                    <td className="py-4 px-4">
                      <span className="rounded-full bg-emerald-100 px-2.5 py-0.5 text-[9px] font-bold text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30">
                        {cmp.status}
                      </span>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      </div>

      {/* Add Campaign Modal */}
      {showAddModal ? (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/75 p-4 backdrop-blur-md">
          <div className="w-full max-w-md rounded-3xl border border-slate-200 bg-white p-6 text-slate-900 shadow-2xl backdrop-blur-2xl dark:border-slate-800 dark:bg-slate-900 dark:text-white space-y-4">
            <div className="flex items-center justify-between border-b border-slate-100 pb-3 dark:border-slate-800">
              <h2 className="text-base font-bold">New Campaign Integration</h2>
              <button onClick={() => setShowAddModal(false)} className="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <div className="space-y-3 text-xs">
              <div>
                <label className="block font-bold text-slate-600 dark:text-slate-400 mb-1">Campaign Name</label>
                <input
                  type="text"
                  placeholder="e.g. Instagram Reels Medical 2026"
                  value={newCampaign.name}
                  onChange={(e) => setNewCampaign({ ...newCampaign, name: e.target.value })}
                  className="w-full rounded-xl border border-slate-200 bg-slate-50 p-2.5 font-medium text-slate-900 focus:border-indigo-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white"
                />
              </div>

              <div>
                <label className="block font-bold text-slate-600 dark:text-slate-400 mb-1">Platform</label>
                <select
                  value={newCampaign.platform}
                  onChange={(e) => setNewCampaign({ ...newCampaign, platform: e.target.value as any })}
                  className="w-full rounded-xl border border-slate-200 bg-slate-50 p-2.5 font-medium text-slate-900 focus:border-indigo-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white"
                >
                  <option value="facebook">Facebook Ads</option>
                  <option value="instagram">Instagram Reels</option>
                  <option value="google">Google Search</option>
                  <option value="website">Website Lead Form</option>
                  <option value="whatsapp">WhatsApp Direct</option>
                </select>
              </div>

              <div>
                <label className="block font-bold text-slate-600 dark:text-slate-400 mb-1">Budget (₹)</label>
                <input
                  type="number"
                  value={newCampaign.budget}
                  onChange={(e) => setNewCampaign({ ...newCampaign, budget: Number(e.target.value) })}
                  className="w-full rounded-xl border border-slate-200 bg-slate-50 p-2.5 font-medium text-slate-900 focus:border-indigo-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white"
                />
              </div>
            </div>

            <div className="flex justify-end gap-2 pt-2">
              <button
                onClick={() => setShowAddModal(false)}
                className="rounded-xl border border-slate-200 bg-slate-100 px-4 py-2 text-xs font-bold text-slate-700 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-300"
              >
                Cancel
              </button>
              <button
                onClick={handleCreateCampaign}
                className="rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-4 py-2 text-xs font-bold text-white shadow-md hover:opacity-90"
              >
                Create Campaign
              </button>
            </div>
          </div>
        </div>
      ) : null}
    </AppShell>
  );
}
