import React from "react";
import Button from "@/Components/Default/Button";
import Guest from "@/Layouts/Guest";
import { Head, Link, useForm } from "@inertiajs/inertia-react";

export default function VerifyEmail({ status }) {
    const { post, processing } = useForm();

    const submit = (e) => {
        e.preventDefault();

        post(route("verification.send"));
    };

    return (
        <Guest>
            <Head title="Email Verification" />
            <div className="mb-4 text-sm">
                Thanks loh udah daftar di Cuy Universe, Sekarang coba di verifikasi dulu emailnya ya bro. Klik link yang
                udah kita kirim ke email kalian. Kalau belom dapet linknya, kita bakal kirim ulang konfirmasinya kok
                kalem.
            </div>

            {status === "verification-link-sent" && (
                <div className="mb-4 font-medium text-sm">
                    Thanks udah daftar bro, silahkan dicek dan dibuka duls emailnya untuk konfirmasi pendaftaran di CUY
                    UNIVERSE
                </div>
            )}

            <form onSubmit={submit}>
                <div className="mt-4 flex items-center justify-between">
                    <Button processing={processing}>Kirim Ulang Verifikasi Email</Button>

                    <Link href={route("logout")} method="post" as="button" className="underline text-sm">
                        Keluar
                    </Link>
                </div>
            </form>
        </Guest>
    );
}
