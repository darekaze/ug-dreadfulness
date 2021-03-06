package java_11;
// Name: JAHJA Darwin
// StudentID: 16094501d
// Load test
public class LCS_Q2 {
    public static void main(String argv[]) {
        // Max length for s1, s2, and s3 is 940 tested on my laptop
        String s1 = "ecwoudcosfznghsgtvqjwqjvholnqvcfzjbqgiidgzmmapyovmolevwsbqwxzpjfcdtcbbxvthehrzhiilnzmroawrtgjxwhrmbskxfhblcidlvyxxgcoanawlkbzathihhmucwutzlhtglnuykiuenclttzozpmzclgxscipztqagikvfpqjgcqdctqrrlrkmnsdluewncefwiagprhlyberzyluxdnkzybgjqzeemnhkuxtflvsumngcwsircpuuievermdlcaosayyeauifjuzpaitfyuysvaepqwojfwroxnnxlcspnyoyijtvimddcixqpfrayllbfbdxabbqcfgrealtanfvgbwrevvtvszpupypvlxhnjpaomsqkcoskxfgxubzosicybbwuzryyywkzuhuqfrujlyljjfnabgixytqjwhoxcajmpckamzpvnjhsmwjjdrdwwiarpbvvchqmmljyvmybkrsknlxrgmrsulryzbxoqigrtsuyh";
        String s2 = "thcrlfmopghbnzrndqwncoxizaoonthzuservidcuyghpwkvqsbqyfmzysdxdqohesqskvkdgygnrguijrpevgcfhziorgltkpihmeknnirrtkggkfyeorfldzlrmfuxgprkmkomfqpmhcvcvgyvxgqwxyyckdrctpptzaeagkuknvllphyewieenykgxsydcjaofmsfxzltervdayvvzryutyhnsscejnwftsddntpxswhgfasmvxamyczjbnoermrnqllzqvhgiyuzsmgfempofrqbhqzlorcakwgxwtjvvoxniqyastbvxtnvxyxkpfwmxldeowwrvhhborvqsagqaeautdrofgscybfgbhitstsyfugzjwnmnrojdhgwqbnyyqrvfkzevlbhxpodyhtscxplgedztelrioaeyvfrnhaewsllsbieefwepsgzskwlvujdauqjcexliyqllqyhwozwvmzxtaoookyqzizbwwaqzebkcbmaeetyjtli";
        String s3 = "diygxgbtwhnmoctqxnnrwativhdjhyngsstzscuwsnfvntwlvplagesnlsnwapzsfxkbvphdynpgjjhbgixaokzjconijzxqebosrhznubpugjreloqrcaekbjpzvklmtxnohleucqxrmfnliomocaonnewcebzkcvqghzzxfqpvkdydvqmokmzyvhpjfejcswcfxtmygspvzezvfiujzjgvavaqgmeibpbmlooilnethxofvugykjfmcuzgfsogkhxnwezpjxndaybzsfrmxqipngdsulgattahrfxrkbyjvyfzyrtljasqpopiylywmfobdubehthkbcfotvnrxjjltlszfhpqlaworindlnispszaroztxyaqsovoyfpmxitwgkeoakyukmsxrbxtlctyjmeubbjhtwncdpdzypqueqqzapqrbsujilpfciikrsrtdiizgulenlxpqsxsvxqmzlgmnuyuonylzvrdyhpzsbsbeiafjpbcxnqkiehg";
        String res = LCS16094501D.FindLCS(s1, s2, s3);
        System.out.println(res);
    }
}
