"use client";

import React from "react";
import Image from "next/image";
import { Plus, Utensils } from "lucide-react";
import { Button } from "@/components/ui/button";
import {
	Card,
	CardDescription,
	CardFooter,
	CardHeader,
	CardTitle,
} from "@/components/ui/card";

export type MenuItem = {
	id: string;
	name: string;
	price: number;
	desc: string;
	image?: string;
};

interface ProductCardProps {
	item: MenuItem;
	onSelect: (item: MenuItem) => void;
}

export function ProductCard({ item, onSelect }: ProductCardProps) {
	return (
			<Card
					className="overflow-hidden flex flex-col border-muted shadow-sm hover:shadow-md transition-shadow cursor-pointer"
					onClick={() => onSelect(item)}
			>
				<div className="aspect-square bg-secondary/50 relative flex items-center justify-center overflow-hidden">
					{item.image ? (
							<Image
									src={`/products/${item.image}`}
									alt={item.name}
									fill
									className="object-cover transition-transform hover:scale-105 duration-300"
									sizes="(max-width: 768px) 50vw, 33vw"
							/>
					) : (
							<div className="flex flex-col items-center justify-center gap-1.5 text-muted-foreground/50">
								<Utensils className="w-8 h-8 stroke-[1.5]" />
								<span className="text-[11px] font-medium tracking-wide">Cafedev</span>
							</div>
					)}
				</div>

				<CardHeader className="p-3 pb-1 pointer-events-none">
					<CardTitle className="text-sm leading-tight line-clamp-1">
						{item.name}
					</CardTitle>
					<CardDescription className="text-xs line-clamp-1 mt-0.5">
						{item.desc}
					</CardDescription>
				</CardHeader>

				<CardFooter className="p-3 pt-2 mt-auto flex items-center justify-between">
                <span className="font-bold text-sm text-primary">
                    {item.price.toLocaleString("vi-VN")}đ
                </span>
					<Button size="icon" className="h-7 w-7 rounded-full shrink-0">
						<Plus className="w-4 h-4" />
					</Button>
				</CardFooter>
			</Card>
	);
}