"use client";

import { FormEvent, useState } from "react";
import { useRouter } from "next/navigation";
import { api } from "@/lib/api";

type LoginData = {
  token: string;
  user: { name: string; email: string };
};

export default function LoginPage() {
  const router = useRouter();
  const [email, setEmail] = useState("admin@blueventure.test");
  const [password, setPassword] = useState("password");
  const [organizationCode, setOrganizationCode] = useState("BLUEVENTURE");
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);

  async function onSubmit(event: FormEvent) {
    event.preventDefault();
    setError("");
    setLoading(true);

    try {
      const result = await api<LoginData>("/auth/login", {
        method: "POST",
        body: JSON.stringify({
          email,
          password,
          organization_code: organizationCode,
        }),
      });

      localStorage.setItem("bv_token", result.data.token);
      localStorage.setItem("bv_user", JSON.stringify(result.data.user));
      router.push("/dashboard");
    } catch (err) {
      setError(err instanceof Error ? err.message : "Login failed");
    } finally {
      setLoading(false);
    }
  }

  return (
    <main className="flex min-h-screen items-center justify-center bg-[#080C14] px-4 text-white">
      <div className="relative w-full max-w-md">
        {/* Glow backdrop */}
        <div className="absolute -inset-1 rounded-3xl bg-gradient-to-r from-indigo-500 via-purple-600 to-pink-500 opacity-30 blur-2xl" />

        <form
          onSubmit={onSubmit}
          className="relative w-full rounded-3xl border border-slate-800 bg-slate-900/90 p-8 shadow-2xl backdrop-blur-2xl"
        >
          <div className="flex items-center gap-3">
            <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-600 to-purple-600 font-bold text-white shadow-lg">
              🚀
            </div>
            <div>
              <h1 className="text-xl font-extrabold text-white">EduPilot</h1>
              <p className="text-[10px] font-semibold text-indigo-400">
                AI-Powered Student & Admissions CRM
              </p>
            </div>
          </div>

          <p className="mt-4 text-xs text-slate-400">
            Sign in to access your admissions command dashboard.
          </p>

          <div className="mt-6 space-y-4 text-xs">
            <div>
              <label className="block font-semibold text-slate-300">Organization Code</label>
              <input
                className="mt-1 w-full rounded-xl border border-slate-800 bg-slate-950 px-3.5 py-2.5 text-xs text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-hidden"
                value={organizationCode}
                onChange={(e) => setOrganizationCode(e.target.value)}
              />
            </div>
            <div>
              <label className="block font-semibold text-slate-300">Email Address</label>
              <input
                type="email"
                className="mt-1 w-full rounded-xl border border-slate-800 bg-slate-950 px-3.5 py-2.5 text-xs text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-hidden"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
              />
            </div>
            <div>
              <label className="block font-semibold text-slate-300">Password</label>
              <input
                type="password"
                className="mt-1 w-full rounded-xl border border-slate-800 bg-slate-950 px-3.5 py-2.5 text-xs text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-hidden"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
              />
            </div>
          </div>

          {error ? <p className="mt-4 text-xs font-semibold text-rose-400">{error}</p> : null}

          <button
            type="submit"
            disabled={loading}
            className="mt-6 w-full rounded-xl bg-gradient-to-r from-indigo-500 via-purple-600 to-pink-500 py-3 font-bold text-white shadow-lg hover:shadow-indigo-500/25 disabled:opacity-60 transition-all"
          >
            {loading ? "Authenticating..." : "Sign In to Command Center"}
          </button>
        </form>
      </div>
    </main>
  );
}
