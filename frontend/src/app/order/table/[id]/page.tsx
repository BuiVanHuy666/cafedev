"use client";

import React, { useEffect } from "react";
import { useParams, useRouter } from "next/navigation";

export default function TableValidatorPage() {
	const params = useParams();
	const router = useRouter();

	useEffect(() => {
		const tableId = params?.id;
		if (!tableId) {
			router.replace("/order/table");
			return;
		}
		const validateTableId = async (id: string | string[]) => {
			const validMockTables = ["tbl_01", "tbl_02", "uuid_vip_01"];
			await new Promise(resolve => setTimeout(resolve, 800));

			if (typeof id === 'string' && validMockTables.includes(id)) {
				router.replace("/");
			} else {
				router.replace("/order/table?error=invalid_table");
			}
		};

		validateTableId(tableId);

	}, [params, router]);

	return (
			<div className="flex flex-col items-center justify-center min-h-screen bg-background">
				<div className="w-12 h-12 border-4 border-primary border-t-transparent rounded-full animate-spin mb-4" />
				<p className="text-muted-foreground font-medium animate-pulse">
					Đang xác nhận thông tin bàn...
				</p>
			</div>
	);
}