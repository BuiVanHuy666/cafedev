import Link from "next/link";
import { cn } from "@/lib/utils";
import { AnchorHTMLAttributes } from "react";

interface NavButtonProps extends AnchorHTMLAttributes<HTMLAnchorElement> {
	href: string;
	children: React.ReactNode;
}

export function NavButton({ href, children, className, ...props }: NavButtonProps) {
	return (
			<Link href={href}
			      className={cn(
							"inline-flex items-center justify-center whitespace-nowrap",
							"bg-[#1a73e8] hover:bg-[#155ebb] text-white",
							"rounded-full",
							"h-12 px-8 text-[17px] font-bold tracking-wide",
							"transition-all active:scale-[0.98] shadow-sm",
							className
					)}
					{...props}
			>
				{children}
			</Link>
	);
}