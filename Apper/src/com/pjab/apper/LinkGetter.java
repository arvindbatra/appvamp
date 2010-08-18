package com.pjab.apper;




import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.MalformedURLException;
import java.net.URL;
import java.util.ArrayList;
import java.util.List;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

public class LinkGetter {
	private Pattern htmltag;
	private Pattern link;
	private final String root;

	public LinkGetter(String root) {
		this.root = root;
		htmltag = Pattern.compile("<a\\b[^>]*href=\"[^>]*>(.*?)</a>");
		link = Pattern.compile("href=\"[^>]*\">");
	}

	public List<String> getLinks(String origUrl, String page) {
		List<String> links = new ArrayList<String>();
		StringBuilder builder = new StringBuilder();
		builder.append(page);
			
		Matcher tagmatch = htmltag.matcher(builder.toString());
		while (tagmatch.find()) {
			Matcher matcher = link.matcher(tagmatch.group());
			if(matcher.find())
			{
				String link = matcher.group().replaceFirst("href=\"", "")
			
					.replaceFirst("\">", "");
				if (valid(link)) {
					links.add(link);
				}
			}
		}
		return links;
	}

	private boolean valid(String s) {
		if (s.matches("javascript:.*|mailto:.*")) {
			return false;
		}
		return true;
	}

	private String makeAbsolute(String url, String link) {
		if (link.matches("http://.*")) {
			return link;
		}
		if (link.matches("/.*") && url.matches(".*$[^/]")) {
			return url + "/" + link;
		}
		if (link.matches("[^/].*") && url.matches(".*[^/]")) {
			return url + "/" + link;
		}
		if (link.matches("/.*") && url.matches(".*[/]")) {
			return url + link;
		}
		if (link.matches("/.*") && url.matches(".*[^/]")) {
			return url + link;
		}
		throw new RuntimeException("Cannot make the link absolute. Url: " + url
				+ " Link " + link);
	}
}

