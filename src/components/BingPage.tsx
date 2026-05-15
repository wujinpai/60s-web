import { Download, ExternalLink, Image, RefreshCw } from "lucide-react";
import { useApi } from "../hooks/useApi";
import { CardTitle, EmptyState } from "./ui";

export type BingWallpaper = {
	title?: string;
	headline?: string;
	description?: string;
	main_text?: string;
	cover?: string;
	cover_4k?: string;
	copyright?: string;
	update_date?: string;
	update_date_at?: number;
};

export function BingPage({ apiBase }: { apiBase: string }) {
	const bing = useApi<BingWallpaper>(apiBase, "/bing", {}, true);
	const imageUrl = bing.data?.cover_4k || bing.data?.cover;

	const handleDownload = () => {
		if (!imageUrl) return;
		const link = document.createElement("a");
		link.href = imageUrl;
		link.download = `bing-wallpaper-${new Date().toISOString().slice(0, 10)}.jpg`;
		link.click();
	};

	return (
		<section className="page-stack">
			<div className="page-title">
				<span>
					<Image size={24} /> 每日一图
				</span>
			</div>
			<article className="card bing-card">
				<CardTitle
					icon={<Image size={22} />}
					title="Bing 每日壁纸"
				/>
				{bing.loading ? (
					<div className="bing-loading">
						<div className="loading-spinner" />
						<span>加载中...</span>
					</div>
				) : bing.error ? (
					<EmptyState title="加载失败" desc={bing.error} />
				) : imageUrl ? (
					<>
						<div className="bing-actions">
							{imageUrl && (
								<>
									<button className="outline-button" onClick={handleDownload}>
										<Download size={17} /> 下载
									</button>
									<a
										className="outline-button"
										href={imageUrl}
										target="_blank"
										rel="noreferrer"
									>
										<ExternalLink size={17} /> 原图
									</a>
								</>
							)}
							<button className="outline-button" onClick={bing.reload}>
								<RefreshCw size={17} /> 刷新
							</button>
						</div>
						<div className="bing-image-container">
							<img
								src={imageUrl}
								alt={bing.data?.title || "Bing 每日壁纸"}
								className="bing-image"
							/>
						</div>
						<div className="bing-info">
							{bing.data?.headline && <h4 className="bing-headline">{bing.data.headline}</h4>}
							{bing.data?.title && <h3>{bing.data.title}</h3>}
							{bing.data?.description && (
								<p className="bing-description">{bing.data.description}</p>
							)}
							{bing.data?.main_text && (
								<p className="bing-main-text">{bing.data.main_text}</p>
							)}
							{bing.data?.copyright && (
								<p className="bing-copyright">来源: {bing.data.copyright}</p>
							)}
							{bing.data?.update_date && (
								<p className="bing-update-date">更新时间: {bing.data.update_date}</p>
							)}
						</div>
					</>
				) : (
					<EmptyState
						title="暂无图片"
						desc="暂时无法获取 Bing 每日壁纸，请稍后重试。"
					/>
				)}
			</article>
		</section>
	);
}