import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import DeleteUserForm from './Partials/DeleteUserForm';
import UpdatePasswordForm from './Partials/UpdatePasswordForm';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm';
import { Head, Link } from '@inertiajs/react';
import Guest from "@/Layouts/GuestLayout.jsx";
import PrimaryButton from "@/Components/PrimaryButton.jsx";

export default function Edit({ auth, mustVerifyEmail, status, url }) {
    return (
        <Guest
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Profile</h2>}
        >
            <Head title="Profile" />

            <div className="p-12">
                <h1>{auth.user.name}</h1>
                <a href={url}>
                <PrimaryButton className="ms-4">
                    Pay Now
                </PrimaryButton>
                </a>
            </div>
        </Guest>
    );
}
