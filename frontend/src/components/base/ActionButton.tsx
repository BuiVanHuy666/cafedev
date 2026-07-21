import { cn } from "@/lib/utils";
import { ButtonHTMLAttributes } from "react";
import { Button } from "@/components/ui/button";

interface ActionButtonProps extends ButtonHTMLAttributes<HTMLButtonElement> {
	children: React.ReactNode;
	variant?: "default" | "outline";
}

export function ActionButton({
	children,
	variant = "default",
	className,
	...props
}: ActionButtonProps) {
	return (
			<Button
					className={cn(
							"inline-flex items-center justify-center whitespace-nowrap cursor-pointer",
							"rounded-full h-9.5 px-5 text-[15px] font-medium tracking-wide",
							"transition-all active:scale-[0.96]",
							variant === "default" && "bg-[#0071e3] hover:bg-[#0077ED] text-white",
							variant === "outline" && "bg-transparent border border-[#0071e3] text-[#0071e3] hover:bg-[#0071e3]/5",
							className
					)}
					{...props}
			>
				{children}
			</Button>
	);
}