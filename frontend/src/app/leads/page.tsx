"use client";

import Link from "next/link";
import { useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import { api } from "@/lib/api";

type Lead = {
  id: number;
  lead_status: string;
  priority: string;
  temperature: string;
  student?: { first_name: string; last_name?: string; phone: string };
};

export default function LeadsPage() {
  const router = useRouter();
  const [leads, setLeads] = useState<Lead[]>([]);
  const [firstName, setFirstName] = useState("");
  const [phone, setPhone] = useState("");
  const [error, setError] = useState("");

  function token() {
    return localStorage.getItem("bv_token");
  }

  async function load(currentToken: string) {
    const result = await api<Lead[]>("/leads", {}, currentToken);
    setLeads(result.data);
  }

  useEffect(() => {
    const current = token();
    if (!current) {
      router.replace("/");
      return;
    }
    load(current).catch((err) => setError(err instanceof Error ? err.message : "Failed"));
  }, [router]);

  async function onSubmit(event: React.FormEvent) {
    event.preventDefault();
    const current = token();
    if (!current) return;

    try {
      await api(
        "/leads",
        {
          method: "POST",
          body: JSON.stringify({ first_name: firstName, phone, priority: "medium" }),
        },
        current
      );
      setFirstName("");
      setPhone("");
      await load(current);
    } catch (err) {
      setError(err instanceof Error ? err.message : "Create failed");
    }
  }

  return (
    <main className="min-h-screen bg-slate-50 p-8">
      <div className="mx-auto max-w-5xl">
        <Link href="/dashboard" className="text-sm text-indigo-600">
          Back to dashboard
        </Link>
        <h1 className="mt-2 text-2xl font-semibold">Leads</h1>

        <form onSubmit={onSubmit} className="mt-6 flex flex-wrap gap-3 rounded-xl bg-white p-4 shadow-sm">
          <input
            className="rounded-lg border px-3 py-2"
            placeholder="First name"
            value={firstName}
            onChange={(e) => setFirstName(e.target.value)}
            required
          />
          <input
            className="rounded-lg border px-3 py-2"
            placeholder="Phone"
            value={phone}
            onChange={(e) => setPhone(e.target.value)}
            required
          />
          <button className="rounded-lg bg-indigo-600 px-4 py-2 text-white">Create lead</button>
        </form>

        {error ? <p className="mt-4 text-red-600">{error}</p> : null}

        <div className="mt-6 overflow-hidden rounded-xl bg-white shadow-sm">
          <table className="w-full text-left text-sm">
            <thead className="bg-slate-100 text-slate-600">
              <tr>
                <th className="px-4 py-3">Student</th>
                <th className="px-4 py-3">Phone</th>
                <th className="px-4 py-3">Status</th>
                <th className="px-4 py-3">Priority</th>
                <th className="px-4 py-3">Temperature</th>
              </tr>
            </thead>
            <tbody>
              {leads.map((lead) => (
                <tr key={lead.id} className="border-t">
                  <td className="px-4 py-3">
                    {lead.student?.first_name} {lead.student?.last_name}
                  </td>
                  <td className="px-4 py-3">{lead.student?.phone}</td>
                  <td className="px-4 py-3">{lead.lead_status}</td>
                  <td className="px-4 py-3">{lead.priority}</td>
                  <td className="px-4 py-3">{lead.temperature}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </main>
  );
}
