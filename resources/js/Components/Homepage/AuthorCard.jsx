import { Link } from "@inertiajs/inertia-react";

export default function AuthorCard({ is_online, last_seen, total_post, total_comment, author_image, username }) {
    return (
        <Link
            href={`/author/${username}`}
            as="button"
            className="flex flex-col rounded-md shadow-lg w-full md:w-5/6 lg:w-1/3 xl:w-1/4 justify-center items-center cursor-pointer hover:-translate-y-1 hover:transition-all"
        >
            <div className={`avatar ${is_online ? "online" : "offline"}`}>
                <div className="w-16 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                    <img
                        src={
                            author_image !== null
                                ? `/storage/images/${author_image}`
                                : "/storage/images/defaultavatar.png"
                        }
                    />
                </div>
            </div>
            <div className="stat">
                <div className="text-md">{username}</div>
                <div className="text-sm">{is_online ? "online" : `offline ${last_seen}`}</div>
                <div className="p-0 m-0 my-2 h-1 bg-base-100 w-full"></div>
                <div className="flex justify-between items-end">
                    <div className="text-sm">
                        {total_post == 0 ? "belum pernah posting" : `memiliki ${total_post} postingan`}
                    </div>
                    <div className="divider divider-horizontal">
                        {total_comment > 10 ? "📖" : total_post > 5 && total_comment > 10 ? "🐱‍💻" : "🗿"}
                    </div>
                    <div className="text-sm">
                        {total_post == 0 ? "belum pernah komentar" : `telah mengomentari ${total_comment} postingan`}
                    </div>
                </div>
            </div>
        </Link>
    );
}
